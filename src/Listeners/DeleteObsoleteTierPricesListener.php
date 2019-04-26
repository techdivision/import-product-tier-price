<?php

/**
 * TechDivision\Import\Product\TierPrice\Listeners\DeleteObsoleteTierPricesListener
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

namespace TechDivision\Import\Product\TierPrice\Listeners;

use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Product\TierPrice\Utils\ConfigurationKeys;
use TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface;

/**
 * After the subject has finished it's processing, this listener causes the obsolete tier prices to be deleted.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class DeleteObsoleteTierPricesListener extends \League\Event\AbstractListener
{

    /**
     * The invoking tier price processor instance.
     *
     * @var \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface
     */
    protected $tierPriceProcessor;

    /**
     * Initializes the listener with the tier price processor.
     *
     * @param \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface $tierPriceProcessor The observer instance
     */
    public function __construct(TierPriceProcessorInterface $tierPriceProcessor)
    {
        $this->tierPriceProcessor = $tierPriceProcessor;
    }

    /**
     * Returns the tier price processor instance.
     *
     * @return \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface The processor instance
     */
    protected function getTierPriceProcessor()
    {
        return $this->tierPriceProcessor;
    }

    /**
     * Handle the event.
     *
     * Deletes the tier prices for all the products, which have been touched by the import,
     * and which were not part of the tier price import.
     *
     * @param \League\Event\EventInterface                   $event   The event that triggered the listener
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject that triggered the listener
     *
     * @return void
     */
    public function handle(\League\Event\EventInterface $event, SubjectInterface $subject = null)
    {

        // query whether or not the tier prices has to be cleaned-up
        /** @var \TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject $subject */
        if ($subject->getConfiguration()->hasParam(ConfigurationKeys::CLEAN_UP_TIER_PRICES)) {
            if ($subject->getConfiguration()->getParam(ConfigurationKeys::CLEAN_UP_TIER_PRICES)) {
                $this->getTierPriceProcessor()->cleanUpTierPrices($subject->getProcessedTierPrices());
            }
        }
    }
}
