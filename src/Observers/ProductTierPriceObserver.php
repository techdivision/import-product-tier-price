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
use TechDivision\Import\Product\TierPrice\Utils\DefaultCodes;
use TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface;
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
     * The available tier price value types.
     *
     * @var \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface
     */
    protected $valueTypes;

    /**
     * Initializes the observer with the tier price value types.
     *
     * @param \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface $valueTypes The tier price value types
     */
    public function __construct(ValueTypesInterface $valueTypes)
    {
        $this->valueTypes = $valueTypes;
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

        // initialize the array for the artefacts
        $artefacts = array();

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

        // load the serialized tier price information from the column
        if ($tierPrices = $this->getValue(ColumnKeys::TIER_PRICES)) {
            // iterate over the found tier prices
            foreach ($this->explode($tierPrices, '|') as $tierPrice) {
                // explode the tier prices
                $explodedTierPrice = $this->getSubject()->explode($tierPrice);
                // build the dictionary
                $tierPriceImportData = array();

                // explode the tier price details
                foreach ($explodedTierPrice as $assignmentString) {
                    list($key, $value) = $this->explode($assignmentString, '=');
                    $tierPriceImportData[$key] = trim($value);
                }

                // load the quantity and the price for the tier price
                $qty = isset($tierPriceImportData[ColumnKeys::QTY]) ? $tierPriceImportData[ColumnKeys::QTY] : null;
                $price = isset($tierPriceImportData[ColumnKeys::PRICE]) ? $tierPriceImportData[ColumnKeys::PRICE] : null;

                // validate quantity and price
                if ($qty == null || $price == null) {
                    throw new \Exception(
                        $this->appendExceptionSuffix(
                            sprintf('Missing qty or price for tier price for product "%s"', $sku)
                        )
                    );
                }

                // load the other values from the extracted data
                $valueType = $this->getValueType($tierPriceImportData);
                $website = $this->getWebsiteCode($tierPriceImportData);
                $customerGroup = $this->getCustomerGroupCode($tierPriceImportData);
                $tierpriceWebsites = $this->explode($this->getValue(ColumnKeys::PRODUCT_WEBSITES));

                if ($website === DefaultCodes::ALL_WEBSITES || in_array($website, $tierpriceWebsites)) {
                    // prepare the artefact we want to export
                    $artefacts = $this->createArtefact($sku, $qty, $price, $valueType, $website, $customerGroup, $artefacts);
                } else {
                    $this->getSubject()->getSystemLogger()->warning(
                        sprintf(
                            "The Product with the SKU %s has not assigned to the Website %s for tier prices",
                            $sku,
                            $website
                        )
                    );
                }
            }
            // add the artefact to the observer to be exported later
            $this->addArtefacts($artefacts);
        }
    }

    /**
     * Returns the tier price value type from the passed data, or the default one (fixed), if not set.
     *
     * @param array $data The data to load the tier price value type from
     *
     * @return string The tier price value type
     */
    protected function getValueType(array $data)
    {
        return isset($data[ColumnKeys::VALUE_TYPE]) ? $data[ColumnKeys::VALUE_TYPE] : $this->getValueTypes()->getFixed();
    }

    /**
     * Returns the website code from the passed data, or 'All Websites', if not set.
     *
     * @param array $data The data to load the website codefrom
     *
     * @return string The website code
     */
    protected function getWebsiteCode(array $data)
    {
        return $data[ColumnKeys::WEBSITE] ? $data[ColumnKeys::WEBSITE] : DefaultCodes::ALL_WEBSITES;
    }

    /**
     * Returns the customer group code from the passed data, or 'ALL GROUPS', if not set.
     *
     * @param array $data The data to load the customer group code from
     *
     * @return string The customer group code
     */
    protected function getCustomerGroupCode(array $data)
    {
        return $data[ColumnKeys::CUSTOMER_GROUP] ? $data[ColumnKeys::CUSTOMER_GROUP] : DefaultCodes::ALL_GROUPS;
    }

    /**
     * Create's and return's a new empty artefact entity.
     *
     * @param array $columns             The array with the column data
     * @param array $originalColumnNames The array with a mapping from the old to the new column names
     *
     * @return array The new artefact entity
     */
    protected function newArtefact(array $columns, array $originalColumnNames)
    {
        return $this->getSubject()->newArtefact($columns, $originalColumnNames);
    }

    /**
     * Add the passed product type artefacts to the product with the
     * last entity ID.
     *
     * @param array $artefacts The product type artefacts
     *
     * @return void
     * @uses \TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject::getLastEntityId()
     */
    protected function addArtefacts(array $artefacts)
    {
        $this->getSubject()->addArtefacts(ProductTierPriceObserver::ARTEFACT_TYPE, $artefacts);
    }

    /**
     * @param string $sku           Sku
     * @param string $qty           Quantity
     * @param string $price         Price
     * @param string $valueType     valueType
     * @param string $website       Website
     * @param string $customerGroup Customer Group
     * @param array  $artefacts     Artefact
     * @return array
     */
    protected function createArtefact(
        string $sku,
        string $qty,
        string $price,
        string $valueType,
        string $website,
        string $customerGroup,
        array $artefacts
    ) {
    // prepare the artefact we want to export
        $artefacts[] = $this->newArtefact(
            array(
                ColumnKeys::SKU => $sku,
                ColumnKeys::TIER_PRICE_QTY => $qty,
                ColumnKeys::TIER_PRICE => $price,
                ColumnKeys::TIER_PRICE_VALUE_TYPE => $valueType,
                ColumnKeys::TIER_PRICE_WEBSITE => $website,
                ColumnKeys::TIER_PRICE_CUSTOMER_GROUP => $customerGroup
            ),
            array(
                ColumnKeys::SKU => ColumnKeys::SKU,
                ColumnKeys::TIER_PRICE_QTY => ColumnKeys::TIER_PRICES,
                ColumnKeys::TIER_PRICE => ColumnKeys::TIER_PRICES,
                ColumnKeys::TIER_PRICE_VALUE_TYPE => ColumnKeys::TIER_PRICES,
                ColumnKeys::TIER_PRICE_WEBSITE => ColumnKeys::TIER_PRICES,
                ColumnKeys::TIER_PRICE_CUSTOMER_GROUP => ColumnKeys::TIER_PRICES
            )
        );
        return $artefacts;
    }
}
