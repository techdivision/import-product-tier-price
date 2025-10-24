<?php

/**
 * TechDivision\Import\Product\TierPrice\Listeners\DeleteObsoleteTierPricesListener
 *
 * PHP version 7
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */

namespace TechDivision\Import\Product\TierPrice\Listeners;

use TechDivision\Import\Plugins\PluginInterface;
use TechDivision\Import\Services\RegistryProcessorInterface;
use TechDivision\Import\Product\TierPrice\Utils\RegistryKeys;
use TechDivision\Import\Product\Utils\SkuToPkMappingUtilInterface;
use TechDivision\Import\Product\TierPrice\Utils\ConfigurationKeys;
use TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface;

/**
 * After the subject has finished it's processing, this listener causes the obsolete tier prices to be deleted.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   https://opensource.org/licenses/MIT
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
     * The registry processor instance.
     *
     * @var \TechDivision\Import\Services\RegistryProcessorInterface
     */
    protected $registryProcessor;

    /**
     * The invoking tier price processor instance.
     *
     * @var \TechDivision\Import\Product\Utils\SkuToPkMappingUtilInterface
     */
    protected $primarySkuToPkMappingUtil;

    /**
     * Initializes the listener with the tier price processor.
     *
     * @param \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface $tierPriceProcessor The observer instance
     * @param \TechDivision\Import\Services\RegistryProcessorInterface                    $registryProcessor  The registry processor instance
     * @param \TechDivision\Import\Product\Utils\SkuToPkMappingUtilInterface              $skuToPkMappingUtil The observer instance
     */
    public function __construct(
        TierPriceProcessorInterface $tierPriceProcessor,
        RegistryProcessorInterface $registryProcessor,
        SkuToPkMappingUtilInterface $skuToPkMappingUtil
    ) {
        $this->tierPriceProcessor = $tierPriceProcessor;
        $this->registryProcessor = $registryProcessor;
        $this->primarySkuToPkMappingUtil = $skuToPkMappingUtil;
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
     * Returns the registry processor instance.
     *
     * @return \TechDivision\Import\Services\RegistryProcessorInterface
     */
    protected function getRegistryProcessor()
    {
        return $this->registryProcessor;
    }

    /**
     * Returns the tier price processor instance.
     *
     * @return \TechDivision\Import\Product\Utils\SkuToPkMappingUtilInterface The processor instance
     */
    protected function getSkuToPkMappingUtil()
    {
        return $this->primarySkuToPkMappingUtil;
    }

    /**
     * Returns the processed tier prices of the actual import process.
     *
     * @param string $serial The serial of the actual import
     *
     * @return array The array with the IDs of the processed tier prices
     */
    protected function getProcessedTierPrices($serial)
    {

        // load the status for the actual import process
        $status = $this->getRegistryProcessor()->getAttribute(RegistryKeys::STATUS);

        // query whether or not an array with the IDs of the processed tier prices exists
        if (isset($status[RegistryKeys::PROCESSED_TIER_PRICES])) {
            return $status[RegistryKeys::PROCESSED_TIER_PRICES];
        }

        // if not, return an empty array
        return array();
    }

    /**
     * Handle the event.
     *
     * Deletes the tier prices for all the products, which have been touched by the import,
     * and which were not part of the tier price import.
     *
     * @param \League\Event\EventInterface                 $event  The event that triggered the listener
     * @param \TechDivision\Import\Plugins\PluginInterface|null $plugin The plugin that triggered the listener
     *
     * @return void
     */
    public function handle(\League\Event\EventInterface $event, ?PluginInterface $plugin = null)
    {

        // query whether or not the tier prices has to be cleaned-up
        /** @var \TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject $subject */
        if ($plugin->getPluginConfiguration()->hasParam(ConfigurationKeys::CLEAN_UP_TIER_PRICES)) {
            if ($plugin->getPluginConfiguration()->getParam(ConfigurationKeys::CLEAN_UP_TIER_PRICES)) {
                $this->getTierPriceProcessor()
                     ->cleanUpTierPrices(
                         $this->getProcessedTierPrices($plugin->getSerial()),
                         $this->getSkuToPkMappingUtil()->getInvertedSkuToPkMapping($this->getRegistryProcessor(), $plugin->getSerial())
                     );
            }
        }
    }
}
