<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface
 *
 * PHP version 7
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link    https://github.com/techdivision/import-product-tier-price
 * @link    https://www.techdivision.com
 */

namespace TechDivision\Import\Product\TierPrice\Utils;

/**
 * Interface for a utility class containing the available tier price value types.
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link    https://github.com/techdivision/import-product-tier-price
 * @link    https://www.techdivision.com
 */
interface ValueTypesInterface
{

    /**
     * Tier price value type 'fixed'
     *
     * @var string
     */
    const FIXED = 'fixed';

    /**
     * Tier price value type 'discount'
     *
     * @var string
     */
    const DISCOUNT = 'discount';

    /**
     * Query whether or not the passed value type is valid.
     *
     * @param string $valueType The value type to query for
     *
     * @return boolean TRUE if the value type is valid, else FALSE
     */
    public function isValueType($valueType);

    /**
     * Returns the key for the fixed value type.
     *
     * @return string The key
     */
    public function getFixed();

    /**
     * Returns the key for the discount value type.
     *
     * @return string The key
     */
    public function getDiscount();

    /**
     * Queries whether or not the passed value type is fixed.
     *
     * @param string $valueType The value type to query for
     *
     * @return boolean TRUE, if the passed value type is fixed, else FALSE
     */
    public function isFixed($valueType);

    /**
     * Queries whether or not the passed value type is discount.
     *
     * @param string $valueType The value type to query for
     *
     * @return boolean TRUE, if the passed value type is a discount, else FALSE
     */
    public function isDiscount($valueType);
}
