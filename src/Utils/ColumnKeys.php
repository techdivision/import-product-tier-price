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
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
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
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class ColumnKeys extends \TechDivision\Import\Product\Utils\ColumnKeys
{

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
