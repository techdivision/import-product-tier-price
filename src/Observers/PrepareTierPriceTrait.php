<?php

/**
 * TechDivision\Import\Product\TierPrice\Observers\ClearTierPriceUpdateObserver
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

use TechDivision\Import\Product\TierPrice\Utils\ColumnKeys;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;

/**
 * Observer for creating/updating/deleting tier prices from the database.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 */
trait PrepareTierPriceTrait
{

    /**
     * Prepare the attributes of the entity that has to be persisted.
     *
     * @return array The prepared attributes
     */
    protected function prepareAttributes()
    {

        // try to load the entity ID for the product with the passed SKU
        if ($product = $this->loadProduct($sku = $this->getValue(ColumnKeys::SKU))) {
            $this->setLastPk($pk = $product[$this->getPrimaryKeyMemberName()]);
        } else {
            // prepare a log message
            $message = sprintf('Product with SKU "%s" can\'t be loaded to create URL rewrites', $sku);
            // query whether or not we're in debug mode
            if ($this->getSubject()->isDebugMode()) {
                $this->getSubject()->getSystemLogger()->warning($message);
                return $this->getRow();
            } else {
                throw new \Exception($message);
            }
        }

        // load tier price quantity and pricde
        $qty = $this->getValue(ColumnKeys::TIER_PRICE_QTY);
        $price = $this->getValue(ColumnKeys::TIER_PRICE);

        // validate quantity and price
        if ($qty == null || $price == null) {
            throw new \Exception(
                $this->appendExceptionSuffix(
                    sprintf('Missing tier price qty or price for product with SKU "%s"', $sku)
                )
            );
        }

        // validate the value type
        if ($this->getValueTypes()->isValueType($valueType = $this->getValue(ColumnKeys::TIER_PRICE_VALUE_TYPE))) {
            // map website and customer group code into their IDs
            $websiteId = $this->getStoreWebsiteIdByCode($this->getValue(ColumnKeys::TIER_PRICE_WEBSITE));
            $customerGroupId = $this->getCustomerGroupIdByCode($customerGroupCode = $this->getValue(ColumnKeys::TIER_PRICE_CUSTOMER_GROUP));

            // query whether or not the tier price is valid for ALL GROUPS
            $allGroups = (integer) $this->isAllGroups($customerGroupCode);

            // initialize the (percentage) value
            $value = $this->getValueTypes()->isFixed($valueType) ? (double) $price : 0.00;
            $percentageValue = $this->getValueTypes()->isDiscount($valueType) ? (integer) $price : null;

            // initialize the tier price with the given values
            return $this->initializeEntity(
                array(
                    $this->getPrimaryKeyMemberName() => $pk,
                    MemberNames::ALL_GROUPS          => $allGroups,
                    MemberNames::CUSTOMER_GROUP_ID   => $customerGroupId,
                    MemberNames::QTY                 => $qty,
                    MemberNames::VALUE               => $value,
                    MemberNames::WEBSITE_ID          => $websiteId,
                    MemberNames::PERCENTAGE_VALUE    => $percentageValue
                )
            );
        }

        // throw an exception for invalid tier price value types
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Invalid value type "%s" for product with SKU "%s"', $valueType, $sku)
            )
        );
    }

    /**
     * Load's and return's the product with the passed SKU.
     *
     * @param string $sku The SKU of the product to load
     *
     * @return array The product
     */
    abstract protected function loadProduct($sku);

    /**
     * Resolve's the value with the passed colum name from the actual row. If a callback will
     * be passed, the callback will be invoked with the found value as parameter. If
     * the value is NULL or empty, the default value will be returned.
     *
     * @param string        $name     The name of the column to return the value for
     * @param mixed|null    $default  The default value, that has to be returned, if the row's value is empty
     * @param callable|null $callback The callback that has to be invoked on the value, e. g. to format it
     *
     * @return mixed|null The, almost formatted, value
     */
    abstract public function getValue($name, $default = null, callable $callback = null);

    /**
     * Append's the exception suffix containing filename and line number to the
     * passed message. If no message has been passed, only the suffix will be
     * returned
     *
     * @param string|null $message    The message to append the exception suffix to
     * @param string|null $filename   The filename used to create the suffix
     * @param string|null $lineNumber The line number used to create the suffx
     *
     * @return string The message with the appended exception suffix
     */
    abstract protected function appendExceptionSuffix($message = null, $filename = null, $lineNumber = null);

    /**
     * Returns the tier price value types.
     *
     * @return \TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface The tier price value types
     */
    abstract protected function getValueTypes();

    /**
     * Initialize's and return's a new entity with the status 'create'.
     *
     * @param array $attr The attributes to merge into the new entity
     *
     * @return array The initialized entity
     */
    abstract protected function initializeEntity(array $attr = array());

    /**
     * Returns the customer group ID for the given code, if it exists.
     *
     * @param string $code The code of the requested customer group
     *
     * @return integer|null The ID of the customer group
     */
    abstract protected function getCustomerGroupIdByCode($code);

    /**
     * Return's the store website for the passed code.
     *
     * @param string $code The code of the store website to return the ID for
     *
     * @return integer The store website ID
     * @throws \Exception Is thrown, if the store website with the requested code is not available
     */
    abstract protected function getStoreWebsiteIdByCode($code);

    /**
     * Queries whether or not the passed customer group code matches all groups or not.
     *
     * @param string $code The customer group code to query for
     *
     * @return boolean TRUE if the customer group code matches, else FALSE
     */
    abstract protected function isAllGroups($code);

    /**
     * Set's the ID of the last PK used.
     *
     * @param string $pk The PK
     *
     * @return void
     */
    abstract protected function setLastPk($pk);

    /**
     * Returns the primary key member name for the actual Magento edition.
     *
     * @return string The primary key member name
     * @see \TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface::getPrimaryKeyMemberName()
     */
    abstract protected function getPrimaryKeyMemberName();
}
