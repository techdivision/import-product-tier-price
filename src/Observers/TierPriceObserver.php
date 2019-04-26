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

use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface;
use TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface;

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
class TierPriceObserver extends AbstractProductImportObserver
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
        $this->addProcessedTierPrice(
            $this->persistTierPrice($this->initializeTierPrice($this->prepareAttributes())),
            $this->getLastEntityId()
        );
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
}
