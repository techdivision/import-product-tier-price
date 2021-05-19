<?php

/**
 * TechDivision\Import\Product\TierPrice\Observers\TierPriceUpdateObserver
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

namespace TechDivision\Import\Product\TierPrice\Observers;

use TechDivision\Import\Product\Services\ProductBunchProcessorInterface;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface;
use TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface;
use TechDivision\Import\Product\TierPrice\Utils\ColumnKeys;
use TechDivision\Import\Product\TierPrice\Utils\DefaultCodes;

/**
 * Observer for creating/updating/deleting tier prices from the database.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class TierPriceObserver extends AbstractProductTierPriceObserver
{

    /**
     * The trait that prepares the tier price data.
     *
     * @var \TechDivision\Import\Product\TierPrice\Observers\PrepareTierPriceTrait
     */
    use PrepareTierPriceTrait;
    /**
     * The available tier price value types.
     *
     * @var \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface
     */
    protected $valueTypes;

    /**
     * @var ProductBunchProcessorInterface
     */
    protected $productBunchProcessor;

    /**
     * Initialize the observer with the passed product tier price processor instance.
     *
     * @param \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface $tierPriceProcessor    The processor instance
     * @param \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface            $valueTypes            The tier price value types
     * @param \TechDivision\Import\Product\Services\ProductBunchProcessorInterface        $productBunchProcessor The product processor instance
     */
    public function __construct(
        TierPriceProcessorInterface $tierPriceProcessor,
        ValueTypesInterface $valueTypes,
        ProductBunchProcessorInterface $productBunchProcessor
    ) {
        // set the value types
        $this->valueTypes = $valueTypes;
        $this->productBunchProcessor = $productBunchProcessor;

        // pass the tier price processor through to the parent instance
        parent::__construct($tierPriceProcessor);
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
     * Return's the product bunch processor instance.
     *
     * @return ProductBunchProcessorInterface The product bunch processor instance
     */
    protected function getProductBunchProcessor()
    {
        return $this->productBunchProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     * @throws \Exception
     */
    protected function process()
    {

        try {
            // intialize the tier price data
            $tierPriceData = $this->initializeTierPrice($this->prepareAttributes());
            
            if ($tierPriceData['website_id'] === 0) {
                $this->addTierPriceDataToPkMapping($tierPriceData);
            } else {
                $productWebsiteData = $this->getProductBunchProcessor()->loadProductWebsitesBySku(
                    $this->getValue(ColumnKeys::SKU)
                );
                foreach ($productWebsiteData as $productWebsite) {
                    if ($tierPriceData['website_id'] == $productWebsite['website_id']) {
                        // persist the tier price and mark it as processed
                        $this->addTierPriceDataToPkMapping($tierPriceData);
                    } else {
                        $this->getSubject()->getSystemLogger()->warning(
                            sprintf(
                                "The Product with the SKU %s has not assigned to the Website %s",
                                $productWebsite['sku'],
                                $tierPriceData['website_id']
                            )
                        );
                        $this->skipRow();
                        return;
                    }
                }
            }
        } catch (\Exception $e) {
            // query whether or not we're in debug mode
            if ($this->getSubject()->isDebugMode()) {
                $this->getSubject()->getSystemLogger()->warning($e->getMessage());
                $this->skipRow();
                return;
            }
            // throw the exception agatin
            throw $e;
        }
    }
    
    /**s
     * Initialize the product website with the passed attributes and returns an instance.
     *
     * @param array $attr The product website attributes
     *
     * @return array The initialized product website
     * @throws \RuntimeException Is thrown, if the attributes can not be initialized
     */
    protected function initializeTierPrice(array $attr)
    {
        return $attr;
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
     * Persists the tier price with the passed data.
     *
     * @param array       $row  The tier price to persist
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted entity
     */
    protected function persistTierPrice(array $row, $name = null)
    {
        return $this->getTierPriceProcessor()->persistTierPrice($row, $name);
    }

    /**
     * Add the ID of the processed tier price.
     *
     * @param integer $valueId  The ID of the processed tier price
     * @param integer $entityId The entity ID of the related product
     *
     * @return void
     */
    protected function addProcessedTierPrice($valueId, $entityId)
    {
        $this->getSubject()->addProcessedTierPrice($valueId, $entityId);
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
     * @param array $tierPriceData TierPriceData
     * @return void
     */
    protected function addTierPriceDataToPkMapping(array $tierPriceData)
    {
        $this->addProcessedTierPrice(
            $this->persistTierPrice($tierPriceData),
            $pk = $tierPriceData[$this->getPrimaryKeyMemberName()]
        );
        $this->addSkuToPkMapping($this->getValue(ColumnKeys::SKU), $pk);
    }
}
