<?php

/**
 * TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */

namespace TechDivision\Import\Product\TierPrice\Subjects;

use TechDivision\Import\Product\TierPrice\Utils\DefaultCodes;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\TierPrice\Utils\RegistryKeys;
use TechDivision\Import\Product\TierPrice\Utils\ConfigurationKeys;
use TechDivision\Import\Product\Subjects\AbstractProductSubject;

/**
 * Subject for processing tier prices.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class TierPriceSubject extends AbstractProductSubject
{

    /**
     * The array with the default customer group code mappings.
     *
     * @var array
     */
    protected $customerGroupCodeMappings = array(
        DefaultCodes::ALL_GROUPS => 'NOT LOGGED IN'
    );

    /**
     * The array with the default website code mappings.
     *
     * @var array
     */
    protected $websiteCodeMappings = array(
        DefaultCodes::ALL_WEBSITES => 'admin'
    );

    /**
     * The existing customer groups.
     *
     * @var array
     */
    protected $customerGroups = array();

    /**
     * The array that contains the IDs of the processed tier prices
     *
     * @var array
     */
    protected $processedTierPrices = array();

    /**
     * Intializes the previously loaded global data for exactly one variants.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function setUp($serial)
    {

        // invoke the parent method
        parent::setUp($serial);

        // load the status of the actual import process
        $status = $this->getRegistryProcessor()->getAttribute($serial);

        // load the available customer groups from the registry
        $this->customerGroups = $status[RegistryKeys::GLOBAL_DATA][RegistryKeys::CUSTOMER_GROUPS];

        // load the value IDs of the processed tier prices
        if (isset($status[RegistryKeys::PROCESSED_TIER_PRICES])) {
            $this->processedTierPrices = $status[RegistryKeys::PROCESSED_TIER_PRICES];
        }

        // merge the customer group code mappings from the configuration
        if ($this->getConfiguration()->hasParam(ConfigurationKeys::CUSTOMER_GROUP_CODE_MAPPINGS)) {
            foreach ($this->getConfiguration()->hasParam(ConfigurationKeys::CUSTOMER_GROUP_CODE_MAPPINGS) as $row => $system) {
                $this->customerGroupCodeMappings[$row] = $system;
            }
        }

        // merge the website code mappings from the configuration
        if ($this->getConfiguration()->hasParam(ConfigurationKeys::WEBSITE_CODE_MAPPINGS)) {
            foreach ($this->getConfiguration()->hasParam(ConfigurationKeys::WEBSITE_CODE_MAPPINGS) as $row => $system) {
                $this->websiteCodeMappings[$row] = $system;
            }
        }
    }

    /**
     * Clean up the global data after importing the bunch.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function tearDown($serial)
    {

        // load the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // update the status
        $registryProcessor->mergeAttributesRecursive(
            $serial,
            array(
                RegistryKeys::PROCESSED_TIER_PRICES => $this->processedTierPrices
            )
        );

        // invoke the parent method
        parent::tearDown($serial);
    }

    /**
     * Add the ID of the processed tier price.
     *
     * @param integer $valueId  The ID of the processed tier price
     * @param integer $entityId The entity ID of the related product
     *
     * @return void
     */
    public function addProcessedTierPrice($valueId, $entityId)
    {
        $this->processedTierPrices[$valueId][] = $entityId;
    }

    /**
     * Returns the array with the processed tier prices.
     *
     * @return array The array with the processed tier prices
     */
    public function getProcessedTierPrices()
    {
        return $this->processedTierPrices;
    }

    /**
     * Returns the customer group ID for the given code, if it exists.
     *
     * @param string $code The code of the requested customer group
     *
     * @return integer The ID of the customer group
     * @throws \Exception Is thrown, if the customer group with the passed ID is NOT available
     */
    public function getCustomerGroupIdByCode($code)
    {

        // map the customer group code and query whether or not the customer group with the passed code is available
        if (isset($this->customerGroups[$code = $this->mapCustomerGroupCode($code)])) {
            return (integer) $this->customerGroups[$code][MemberNames::CUSTOMER_GROUP_ID];
        }

        // throw an exception if the customer group with the passed code is NOT available
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Found invalid customer group "%s"', $code)
            )
        );
    }

    /**
     * Return's the store website for the passed code.
     *
     * @param string $code The code of the store website to return the ID for
     *
     * @return integer The store website ID
     * @throws \Exception Is thrown, if the store website with the requested code is not available
     * @see \TechDivision\Import\Product\Subjects\AbstractProductSubject::getStoreWebsiteIdByCode()
     */
    public function getStoreWebsiteIdByCode($code)
    {
        return parent::getStoreWebsiteIdByCode($this->mapWebsiteCode($code));
    }

    /**
     * Queries whether or not the passed customer group code matches all groups or not.
     *
     * @param string $code The customer group code to query for
     *
     * @return boolean TRUE if the customer group code matches, else FALSE
     */
    public function isAllGroups($code)
    {
        return DefaultCodes::ALL_GROUPS === strtoupper($code);
    }

    /**
     * Maps the passed customer group code, if a mapping is available.
     *
     * @param string $code The customer group code to map
     *
     * @return string The mapped customer group code
     */
    protected function mapCustomerGroupCode($code)
    {
        return isset($this->customerGroupCodeMappings[$code]) ? $this->customerGroupCodeMappings[$code] : $code;
    }

    /**
     * Maps the passed website code, if a mapping is available.
     *
     * @param string $code The website code to map
     *
     * @return string The mapped website code
     */
    protected function mapWebsiteCode($code)
    {
        return isset($this->websiteCodeMappings[$code]) ? $this->websiteCodeMappings[$code] : $code;
    }
}
