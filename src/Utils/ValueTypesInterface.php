<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\ValueTypesInterface
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
 * Interface for a utility class containing the available tier price value types.
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
