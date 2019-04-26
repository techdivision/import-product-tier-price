<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\ValueTypes
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link    https://github.com/techdivision/import-product-tier-price
 * @link    https://www.techdivision.com
 */

namespace TechDivision\Import\Product\TierPrice\Utils;

/**
 * Utility class containing the available tier price value types.
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link    https://github.com/techdivision/import-product-tier-price
 * @link    https://www.techdivision.com
 */
class ValueTypes extends \ArrayObject implements ValueTypesInterface
{

    /**
     * Construct a new value types instance.
     *
     * @link http://www.php.net/manual/en/arrayobject.construct.php
     */
    public function __construct()
    {

        // initialize the parent class with the available value types
        parent::__construct(
            array(
                ValueTypes::FIXED,
                ValueTypes::DISCOUNT,
            )
        );
    }

    /**
     * Query whether or not the passed value type is valid.
     *
     * @param string $valueType The value type to query for
     *
     * @return boolean TRUE if the value type is valid, else FALSE
     */
    public function isValueType($valueType)
    {
        return in_array(strtolower($valueType), (array) $this);
    }

    /**
     * Queries whether or not the passed value type is fixed.
     *
     * @param string $valueType The value type to query for
     *
     * @return boolean TRUE, if the passed value type is fixed, else FALSE
     */
    public function isFixed($valueType)
    {
        return ValueTypesInterface::FIXED === strtolower($valueType);
    }

    /**
     * Queries whether or not the passed value type is discount.
     *
     * @param string $valueType The value type to query for
     *
     * @return boolean TRUE, if the passed value type is a discount, else FALSE
     */
    public function isDiscount($valueType)
    {
        return ValueTypesInterface::DISCOUNT === strtolower($valueType);
    }
}
