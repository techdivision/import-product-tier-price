<?php

/**
 * TechDivision\Import\Product\TierPrice\Observers\ProductTierPriceObserver
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

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Product\TierPrice\Utils\ColumnKeys;
use TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * Observer for creating a separate import file for the tier price data.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class ProductTierPriceObserver extends AbstractProductImportObserver
{

    /**
     * The artefact type.
     *
     * @var string
     */
    const ARTEFACT_TYPE = 'tier-price';

    /**
     * The tier price artefacts that have to be exported.
     *
     * @var array
     */
    protected $artefacts = array();

    /**
     * Process the observer's business logic.
     *
     * @return void
     * @throws \Exception
     */
    protected function process()
    {

        // initialize the array for the artefacts
        $this->artefacts = array();

        // load the SKU from the row
        $sku = $this->getValue(ColumnKeys::SKU);

        // prepare the store view code
        $this->getSubject()->prepareStoreViewCode();

        // try to load the store view code
        $storeViewCode = $this->getSubject()->getStoreViewCode(StoreViewCodes::ADMIN);

        // mapping only possible for admin store
        if ($storeViewCode != StoreViewCodes::ADMIN) {
            return;
        }

        // nothing to do, if no import data available
        if (!$this->hasValue(ColumnKeys::TIER_PRICES)) {
            return;
        }

        // load the serialized tier price information from the column
        $tierPrices = $this->getValue(ColumnKeys::TIER_PRICES);

        // iterate over the found tier prices
        foreach ($this->explode($tierPrices, '|') as $tierPrice) {
            // explode the tier prices
            $explodedTierPrice = $this->getSubject()->explode($tierPrice);
            // build the dictionary
            $tierPriceImportData = [];

            // explode the tier price details
            foreach ($explodedTierPrice as $assignmentString) {
                list($key, $value) = $this->explode($assignmentString, '=');
                $tierPriceImportData[$key] = trim($value);
            }

            // load the quantity and the price for the tier price
            $qty = $tierPriceImportData['qty'] ? $tierPriceImportData['qty'] : null;
            $price = $tierPriceImportData['price'] ? $tierPriceImportData['price'] : null;

            // validate quantity and price
            if (!$qty || !$price) {
                throw new \Exception(sprintf('Missing qty or price for tier price for product %s: %d', $sku, $tierPrice));
            }

            // load the other values
            $valueType = ($tierPriceImportData['value_type'] ? $tierPriceImportData['value_type'] : null) ?: TierPriceSubject::VALUE_TYPE_FIXED;
            $website = ($tierPriceImportData['website'] ? $tierPriceImportData['website'] : null) ?: TierPriceSubject::WEBSITE_CODE_ALL_WEBSITES;
            $customerGroup = ($tierPriceImportData['customer_group'] ? $tierPriceImportData['customer_group'] : null) ?: TierPriceSubject::CUSTOMER_GROUP_CODE_ALL_GROUPS;

            // prepare the artefact we want to export
            $this->artefacts[] = $this->getSubject()->newArtefact(
                array(
                    ColumnKeys::SKU                       => $sku,
                    ColumnKeys::TIER_PRICE_QTY            => $qty,
                    ColumnKeys::TIER_PRICE                => $price,
                    ColumnKeys::TIER_PRICE_VALUE_TYPE     => $valueType,
                    ColumnKeys::TIER_PRICE_WEBSITE        => $website,
                    ColumnKeys::TIER_PRICE_CUSTOMER_GROUP => $customerGroup
                ),
                array(
                    ColumnKeys::SKU => ColumnKeys::SKU
                )
            );
        }

        // add the artefact to the observer to be exported later
        $this->getSubject()->addArtefacts(self::ARTEFACT_TYPE, $this->artefacts);
    }
}
