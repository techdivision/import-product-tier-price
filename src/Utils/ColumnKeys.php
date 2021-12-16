<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\ColumnKeys
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

namespace TechDivision\Import\Product\TierPrice\Utils;

/**
 * Specific column keys for handling tier prices.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class ColumnKeys extends \TechDivision\Import\Product\Utils\ColumnKeys
{

    /**
     * Name for the column `customer_group`.
     *
     * @var string
     */
    const CUSTOMER_GROUP = 'customer_group';

    /**
     * Name for the column `website`.
     *
     * @var string
     */
    const WEBSITE = 'website';

    /**
     * Name for the column `price`.
     *
     * @var string
     */
    const PRICE = 'price';

    /**
     * Name for the column `value_type`.
     *
     * @var string
     */
    const VALUE_TYPE = 'value_type';

    /**
     * Name for the column `tier_prices`.
     *
     * @var string
     */
    const TIER_PRICES = 'tier_prices';

    /**
     * Name for the column `tier_price`.
     *
     * @var string
     */
    const TIER_PRICE = 'tier_price';

    /**
     * Name for the column `tier_price_qty`.
     *
     * @var string
     */
    const TIER_PRICE_QTY = 'tier_price_qty';

    /**
     * Name for the column `tier_price_value_type`.
     *
     * @var string
     */
    const TIER_PRICE_VALUE_TYPE = 'tier_price_value_type';

    /**
     * Name for the column `tier_price_website`.
     *
     * @var string
     */
    const TIER_PRICE_WEBSITE = 'tier_price_website';

    /**
     * Name for the column `tier_price_customer_group`.
     *
     * @var string
     */
    const TIER_PRICE_CUSTOMER_GROUP = 'tier_price_customer_group';
}
