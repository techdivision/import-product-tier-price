<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\DefaultCodes
 *
 * PHP version 7
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 */

namespace TechDivision\Import\Product\TierPrice\Utils;

/**
 * Specific column keys for handling tier prices.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 */
class DefaultCodes
{

    /**
     * The tier price customer group code for all groups.
     *
     * @var string
     */
    const ALL_GROUPS = 'ALL GROUPS';

    /**
     * The tier price website code for all websites.
     *
     * @var string
     */
    const ALL_WEBSITES = 'All Websites';

    /**
     * This is a utility class, so protect it against direct
     * instantiation.
     */
    private function __construct()
    {
    }

    /**
     * This is a utility class, so protect it against cloning.
     *
     * @return void
     */
    private function __clone()
    {
    }
}
