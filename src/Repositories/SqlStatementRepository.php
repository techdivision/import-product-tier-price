<?php

/**
 * TechDivision\Import\Product\TierPrice\Repositories\SqlStatementRepository
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

namespace TechDivision\Import\Product\TierPrice\Repositories;

use TechDivision\Import\Product\TierPrice\Utils\SqlStatementKeys;

/**
 * Adds statements specifically required for tier price CRUD operations.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class SqlStatementRepository extends \TechDivision\Import\Repositories\SqlStatementRepository
{

    /**
     * The SQL statements.
     *
     * @var array
     */
    private $statements = array(
        SqlStatementKeys::TIER_PRICES =>
            'SELECT *
               FROM catalog_product_entity_tier_price',
        SqlStatementKeys::DELETE_TIER_PRICE =>
            'DELETE
               FROM catalog_product_entity_tier_price
              WHERE value_id = :value_id',
        SqlStatementKeys::CREATE_TIER_PRICE =>
            'INSERT
               INTO catalog_product_entity_tier_price
                    (all_groups,
                     customer_group_id,
                     qty,
                     value,
                     website_id,
                     percentage_value,
                     row_id)
             VALUES (:all_groups,
                     :customer_group_id,
                     :qty,
                     :value,
                     :website_id,
                     :percentage_value,
                     :row_id)',
        SqlStatementKeys::UPDATE_TIER_PRICE =>
            'UPDATE catalog_product_entity_tier_price
                SET all_groups = :all_groups,
                    customer_group_id = :customer_group_id,
                    qty = :qty,
                    value = :value,
                    website_id = :website_id,
                    percentage_value = :percentage_value,
                    row_id = :row_id
              WHERE value_id = :value_id',
        SqlStatementKeys::CUSTOMER_GROUPS =>
            'SELECT *
                FROM customer_group'
    );

    /**
     * Initialize the the SQL statements.
     */
    public function __construct()
    {

        // call the parent constructor
        parent::__construct();

        // merge the class statements
        foreach ($this->statements as $key => $statement) {
            $this->preparedStatements[$key] = $statement;
        }
    }
}
