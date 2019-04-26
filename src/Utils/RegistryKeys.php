<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\ColumnKeys
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
 * Utility class containing the unique registry keys.
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link    https://github.com/techdivision/import-product-tier-price
 * @link    https://www.techdivision.com
 */
class RegistryKeys extends \TechDivision\Import\Product\Utils\RegistryKeys
{

    /**
     * Name for the column `processed_tier_prices`.
     *
     * @var string
     */
    const PROCESSED_TIER_PRICES = 'processed_tier_prices';
}
