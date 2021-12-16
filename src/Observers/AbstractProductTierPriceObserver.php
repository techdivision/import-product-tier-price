<?php

/**
 * TechDivision\Import\Product\TierPrice\Observers\AbstractProductTierPriceObserver
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 */

namespace TechDivision\Import\Product\TierPrice\Observers;

use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface;

/**
 * Observer for deleting tier prices from the database.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 */
abstract class AbstractProductTierPriceObserver extends AbstractProductImportObserver
{

    /**
     * The product tier price processor instance.
     *
     * @var \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface
     */
    protected $tierPriceProcessor;

    /**
     * Initialize the observer with the passed product tier price processor instance.
     *
     * @param \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface $tierPriceProcessor The processor instance
     */
    public function __construct(TierPriceProcessorInterface $tierPriceProcessor)
    {
        $this->tierPriceProcessor = $tierPriceProcessor;
    }

    /**
     * Returns the product tier price rewrite processor instance.
     *
     * @return \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface The processor instance
     */
    protected function getTierPriceProcessor()
    {
        return $this->tierPriceProcessor;
    }

    /**
     * Returns the primary key member name for the actual Magento edition.
     *
     * @return string The primary key member name
     * @see \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface::getPrimaryKeyMemberName()
     */
    protected function getPrimaryKeyMemberName()
    {
        return $this->getTierPriceProcessor()->getPrimaryKeyMemberName();
    }

    /**
     * Set's the ID of the last PK used.
     *
     * @param string $pk The PK
     *
     * @return void
     */
    protected function setLastPk($pk)
    {
        $this->getSubject()->setLastPk($pk);
    }
}
