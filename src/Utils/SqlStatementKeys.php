<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\SqlStatementKeys
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
 * Adds statement keys specifically required for tier price CRUD operations.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class SqlStatementKeys extends \TechDivision\Import\Utils\SqlStatementKeys
{

    /**
     * The SQL statement to load all product tier prices.
     *
     * @var string
     */
    const TIER_PRICES = 'tier_prices';

    /**
     * The SQL statement to remove an existing product tier price.
     *
     * @var string
     */
    const DELETE_TIER_PRICE = 'delete.tier_price';

    /**
     * The SQL statement to create new tier prices.
     *
     * @var string
     */
    const CREATE_TIER_PRICE = 'create.tier_price';

    /**
     * The SQL statement to update an existing tier price.
     *
     * @var string
     */
    const UPDATE_TIER_PRICE = 'update.tier_price';

    /**
     * The SQL statement to load all customer groups.
     *
     * @var string
     */
    const CUSTOMER_GROUPS = 'customer_groups';
}
