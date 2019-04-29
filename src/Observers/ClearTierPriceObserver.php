<?php

/**
 * TechDivision\Import\Product\TierPrice\Observers\ClearTierPriceObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 */

namespace TechDivision\Import\Product\TierPrice\Observers;

use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface;
use TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface;

/**
 * Observer for deleting tier prices from the database.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 */
class ClearTierPriceObserver extends AbstractProductImportObserver
{

    /**
     * The trait that prepares the tier price data.
     *
     * @var \TechDivision\Import\Product\TierPrice\Observers\PrepareTierPriceTrait
     */
    use PrepareTierPriceTrait;

    /**
     * The product tier price processor instance.
     *
     * @var \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface
     */
    protected $tierPriceProcessor;

    /**
     * The available tier price value types.
     *
     * @var \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface
     */
    protected $valueTypes;

    /**
     * Initialize the observer with the passed product tier price processor instance.
     *
     * @param \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface $tierPriceProcessor The processor instance
     * @param \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface            $valueTypes         The tier price value types
     */
    public function __construct(TierPriceProcessorInterface $tierPriceProcessor, ValueTypesInterface $valueTypes)
    {
        $this->tierPriceProcessor = $tierPriceProcessor;
        $this->valueTypes = $valueTypes;
    }

    /**
     * Returns the product URL rewrite processor instance.
     *
     * @return \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface The processor instance
     */
    protected function getTierPriceProcessor()
    {
        return $this->tierPriceProcessor;
    }

    /**
     * Returns the tier price value types.
     *
     * @return \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface The tier price value types
     */
    protected function getValueTypes()
    {
        return $this->valueTypes;
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     * @throws \Exception
     */
    protected function process()
    {

        // prepare the tier price attributes of the actual row
        $tierPrice = $this->prepareAttributes();

        // load the values for loading the actual tier price
        $qty = $tierPrice[MemberNames::QTY];
        $entityId = $tierPrice[MemberNames::ENTITY_ID];
        $allGroups = $tierPrice[MemberNames::ALL_GROUPS];
        $websiteId = $tierPrice[MemberNames::WEBSITE_ID];
        $customerGroupId = $tierPrice[MemberNames::CUSTOMER_GROUP_ID];

        // delete the tier price if available
        if ($tierPrice = $this->loadTierPriceByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($entityId, $allGroups, $customerGroupId, $qty, $websiteId)) {
            $this->deleteTierPrice(array(MemberNames::VALUE_ID => $tierPrice[MemberNames::VALUE_ID]));
        }
    }

    /**
     * Loads and returns the product with the passed SKU.
     *
     * @param string $sku The SKU of the product to load
     *
     * @return array The product
     */
    protected function loadProduct($sku)
    {
        return $this->getTierPriceProcessor()->loadProduct($sku);
    }

    /**
     * Returns the tier price with the given parameters.
     *
     * @param string  $entityId        The entity ID of the product relation
     * @param integer $allGroups       The flag if all groups are affected or not
     * @param integer $customerGroupId The customer group ID
     * @param integer $qty             The tier price quantity
     * @param integer $websiteId       The website ID the tier price is related to
     *
     * @return array The tier price
     */
    protected function loadTierPriceByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($entityId, $allGroups, $customerGroupId, $qty, $websiteId)
    {
        return $this->getTierPriceProcessor()->loadTierPriceByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($entityId, $allGroups, $customerGroupId, $qty, $websiteId);
    }

    /**
     * Deletes the tierprice with the passed attributes.
     *
     * @param array       $row  The attributes of the entity to delete
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return void
     */
    protected function deleteTierPrice($row, $name = null)
    {
        $this->getTierPriceProcessor()->deleteTierPrice($row, $name);
    }

    /**
     * Returns the customer group ID for the given code, if it exists.
     *
     * @param string $code The code of the requested customer group
     *
     * @return integer|null The ID of the customer group
     */
    protected function getCustomerGroupIdByCode($code)
    {
        return $this->getSubject()->getCustomerGroupIdByCode($code);
    }

    /**
     * Return's the store website for the passed code.
     *
     * @param string $code The code of the store website to return the ID for
     *
     * @return integer The store website ID
     * @throws \Exception Is thrown, if the store website with the requested code is not available
     */
    protected function getStoreWebsiteIdByCode($code)
    {
        return $this->getSubject()->getStoreWebsiteIdByCode($code);
    }

    /**
     * Query whether or not the passed value type is valid.
     *
     * @param string $valueType The value type to query for
     *
     * @return boolean TRUE if the value type is valid, else FALSE
     */
    protected function isValueType($valueType)
    {
        return $this->getValueTypes()->isValueType($valueType);
    }

    /**
     * Queries whether or not the passed customer group code matches all groups or not.
     *
     * @param string $code The customer group code to query for
     *
     * @return boolean TRUE if the customer group code matches, else FALSE
     */
    protected function isAllGroups($code)
    {
        return $this->getSubject()->isAllGroups($code);
    }

    /**
     * Set's the ID of the product that has been created recently.
     *
     * @param string $lastEntityId The entity ID
     *
     * @return void
     */
    protected function setLastEntityId($lastEntityId)
    {
        $this->getSubject()->setLastEntityId($lastEntityId);
    }
}
