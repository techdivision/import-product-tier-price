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
               FROM ${table:catalog_product_entity_tier_price}',
        SqlStatementKeys::TIER_PRICE_BY_PK_AND_ALL_GROUPS_AND_CUSTOMER_GROUP_ID_AND_QTY_AND_WEBSITE_ID =>
            'SELECT *
               FROM ${table:catalog_product_entity_tier_price}
              WHERE ${pk:entity_id} = :${pk:entity_id}
                AND all_groups = :all_groups
                AND customer_group_id = :customer_group_id
                AND qty = :qty
                AND website_id = :website_id',
        SqlStatementKeys::DELETE_TIER_PRICE =>
            'DELETE
               FROM ${table:catalog_product_entity_tier_price}
              WHERE value_id = :value_id',
        SqlStatementKeys::CREATE_TIER_PRICE =>
            'INSERT
               INTO ${table:catalog_product_entity_tier_price}
                    (all_groups,
                     customer_group_id,
                     qty,
                     value,
                     website_id,
                     percentage_value,
                     ${pk:entity_id})
             VALUES (:all_groups,
                     :customer_group_id,
                     :qty,
                     :value,
                     :website_id,
                     :percentage_value,
                     :${pk:entity_id})',
        SqlStatementKeys::UPDATE_TIER_PRICE =>
            'UPDATE ${table:catalog_product_entity_tier_price}
                SET all_groups = :all_groups,
                    customer_group_id = :customer_group_id,
                    qty = :qty,
                    value = :value,
                    website_id = :website_id,
                    percentage_value = :percentage_value,
                    ${pk:entity_id} = :${pk:entity_id}
              WHERE value_id = :value_id'
    );

    /**
     * Initializes the SQL statement repository with the primary key and table prefix utility.
     *
     * @param \IteratorAggregate<\TechDivision\Import\Utils\SqlCompilerInterface> $compilers The array with the compiler instances
     */
    public function __construct(\IteratorAggregate $compilers)
    {

        // pass primary key + table prefix utility to parent instance
        parent::__construct($compilers);

        // compile the SQL statements
        $this->compile($this->statements);
    }
}
