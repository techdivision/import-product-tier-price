<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\SqlStatementKeys
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
 * Adds statement keys specifically required for tier price CRUD operations.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   https://opensource.org/licenses/MIT
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
     * The SQL statement to load the product tier price by the given parameters.
     *
     * @var string
     */
    const TIER_PRICE_BY_PK_AND_ALL_GROUPS_AND_CUSTOMER_GROUP_ID_AND_QTY_AND_WEBSITE_ID = 'tier_price.by.pk.and.all_groups.and.customer_group_id.and.qty.and.website_id';

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
}
