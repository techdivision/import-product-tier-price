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

use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\Product\TierPrice\Utils\ColumnKeys;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject;
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
class TierPriceUpdateObserver extends \TechDivision\Import\Product\Observers\AbstractProductImportObserver
{

    /**
     * The product tier price processor instance.
     *
     * @var \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface
     */
    protected $tierPriceProcessor;

    /**
     * For each imported tier price this array holds the hash of the tier price as key.
     *
     * @var array
     */
    protected $tierPriceImported = [];

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
     * Return's the product URL rewrite processor instance.
     *
     * @return TierPriceProcessorInterface
     */
    protected function getTierPriceProcessor()
    {
        return $this->tierPriceProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return void
     * @throws \Exception
     */
    protected function process()
    {

        // load the subjec
        /** @var \TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject $subject */
        $subject = $this->getSubject();

        // load the tier price processor
        $processor = $this->getTierPriceProcessor();

        // load the product's primary key name
        /** @var \TechDivision\Import\Product\Repositories\ProductRepository $productRepository */
        $productRepository = $processor->getProductRepository();
        $productPrimaryKeyName = $productRepository->getPrimaryKeyName();

        // load the SKU from the row
        $sku = $this->getValue(ColumnKeys::SKU);

        // try load the product by the given SKU
        $product = $productRepository->findOneBySku($sku);

        // query whether or not the procuct is available
        if (!$product) {
            throw new \Exception(sprintf('Could not load product for SKU "%s"', $sku));
        }

        // load tier price quantity and pricde
        $qty = $this->getValue(ColumnKeys::TIER_PRICE_QTY);
        $price = $this->getValue(ColumnKeys::TIER_PRICE);

        // validate quantity and price
        if (!$qty || !$price) {
            throw new \Exception(sprintf('Missing qty or price for tier price for product "%s"', $sku));
        }

        // load the tier price value type
        $valueType = $this->getValue(ColumnKeys::TIER_PRICE_VALUE_TYPE);

        // validate the value type
        if (($valueType != TierPriceSubject::VALUE_TYPE_FIXED) &&
            ($valueType != TierPriceSubject::VALUE_TYPE_DISCOUNT)
        ) {
            throw new \Exception(sprintf('Invalid value type "%s" for product "%s"', $valueType, $sku));
        }

        // load the webiste code
        $websiteCode = $this->getValue(ColumnKeys::TIER_PRICE_WEBSITE);

        // map the website code into the ID
        if ($websiteCode == TierPriceSubject::WEBSITE_CODE_ALL_WEBSITES) {
            $websiteId = TierPriceSubject::WEBSITE_ID_ALL_WEBSITES;
        } else {
            // load the website ID from the subject
            $websiteId = $subject->getStoreWebsiteIdByCode($websiteCode);
            // throw an exception if the website ID is NOT available
            if ($websiteId == null) {
                throw new \Exception(sprintf('Unknown website code "%s" for product "%s"', $websiteCode, $sku));
            }
        }

        // load the customer group code from the column
        $customerGroupCode = $this->getValue(ColumnKeys::TIER_PRICE_CUSTOMER_GROUP);

        // map the customer group code into the ID
        if ($customerGroupCode == TierPriceSubject::CUSTOMER_GROUP_CODE_ALL_GROUPS) {
            $customerGroupId = TierPriceSubject::CUSTOMER_GROUP_ID_ALL_GROUPS;
        } else {
            // load the customer group ID from the subject
            $customerGroupId = $subject->getCustomerGroupIdForCode($customerGroupCode);
            // throw an exception if the customer group ID is NOT available
            if ($customerGroupId == null) {
                throw new \Exception(sprintf('Unknown customer group "%s" for product "%s"', $customerGroupCode, $sku));
            }
        }

        // initialize the tier price with the given values
        $tierPrice = array(
            MemberNames::ALL_GROUPS        => ($customerGroupCode == TierPriceSubject::CUSTOMER_GROUP_CODE_ALL_GROUPS),
            MemberNames::CUSTOMER_GROUP_ID => $customerGroupId,
            MemberNames::QTY               => $qty,
            MemberNames::VALUE             => ($valueType == TierPriceSubject::VALUE_TYPE_FIXED) ? $price : null,
            MemberNames::WEBSITE_ID        => $websiteId,
            MemberNames::PERCENTAGE_VALUE  => ($valueType == TierPriceSubject::VALUE_TYPE_DISCOUNT) ? $price : null,
            $productPrimaryKeyName         => $product[$productPrimaryKeyName]
        );

        // query whether or not the tier price already exists
        if ($existingTierPrice = $processor->matchTierPrice($tierPrice)) {
            // if yes, use the existing value ID
            $tierPrice[MemberNames::VALUE_ID] = $existingTierPrice[MemberNames::VALUE_ID];
            // query whether or not the tier prices are equal, update it if NOT
            if (!$processor->tierPricesEqual($tierPrice, $existingTierPrice)) {
                $tierPrice[EntityStatus::MEMBER_NAME] = EntityStatus::STATUS_UPDATE;
                $processor->persistTierPrice($tierPrice);
            }
        } else {
            $tierPrice[EntityStatus::MEMBER_NAME] = EntityStatus::STATUS_CREATE;
            $tierPrice[MemberNames::VALUE_ID] = $processor->persistTierPrice($tierPrice);
        }

        // mark the tier price as imported
        $this->tierPriceImported[$processor->getTierPriceHash($tierPrice)] = true;
    }

    /**
     * Deletes the tier prices for all the products, which have been touched by the import,
     * and which were not part of the tier price import.
     *
     * @return void
     */
    public function deleteObsoleteTierPrices()
    {

        // load the observers subject
        /** @var \TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject $subject */
        $subject = $this->getSubject();

        // load the tier price processor
        $processor = $this->getTierPriceProcessor();

        // load the primary key for the products
        /** @var \TechDivision\Import\Product\Repositories\ProductRepository $productRepository */
        $productRepository = $processor->getProductRepository();
        $productPrimaryKeyName = $productRepository->getPrimaryKeyName();

        // iterate over the tier prices and delete the obsolete ones
        foreach ($processor->getTierPricesByHash() as $hash => $tierPrice) {
            // if imported, we continue
            if (isset($this->tierPriceImported[$hash])) {
                continue;
            }

            // if the product has been touched by the import, delete the tier price
            if ($subject->rowIdHasBeenProcessed($tierPrice[$productPrimaryKeyName])) {
                $processor->deleteTierPrice([MemberNames::VALUE_ID => $tierPrice[MemberNames::VALUE_ID]]);
            }
        }
    }
}
