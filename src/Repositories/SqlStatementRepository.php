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
use TechDivision\Import\Utils\PrimaryKeyUtilInterface;

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
     * The variable name for the PK.
     *
     * @var string
     */
    const PK_MEMBER_NAME = 'pk_member_name';

    /**
     * The primary key util instance.
     *
     * @var \TechDivision\Import\Utils\PrimaryKeyUtilInterface
     */
    private $primaryKeyUtil;

    /**
     * The SQL statements.
     *
     * @var array
     */
    private $statements = array(
        SqlStatementKeys::TIER_PRICES =>
            'SELECT *
               FROM catalog_product_entity_tier_price',
        SqlStatementKeys::TIER_PRICE_BY_PK_AND_ALL_GROUPS_AND_CUSTOMER_GROUP_ID_AND_QTY_AND_WEBSITE_ID =>
            'SELECT *
               FROM catalog_product_entity_tier_price
              WHERE ${' . SqlStatementRepository::PK_MEMBER_NAME . '} = :${' . SqlStatementRepository::PK_MEMBER_NAME . '}
                AND all_groups = :all_groups
                AND customer_group_id = :customer_group_id
                AND qty = :qty
                AND website_id = :website_id',
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
                     ${' . SqlStatementRepository::PK_MEMBER_NAME . '})
             VALUES (:all_groups,
                     :customer_group_id,
                     :qty,
                     :value,
                     :website_id,
                     :percentage_value,
                     :${' . SqlStatementRepository::PK_MEMBER_NAME . '})',
        SqlStatementKeys::UPDATE_TIER_PRICE =>
            'UPDATE catalog_product_entity_tier_price
                SET all_groups = :all_groups,
                    customer_group_id = :customer_group_id,
                    qty = :qty,
                    value = :value,
                    website_id = :website_id,
                    percentage_value = :percentage_value,
                    ${' . SqlStatementRepository::PK_MEMBER_NAME . '} = :${' . SqlStatementRepository::PK_MEMBER_NAME . '}
              WHERE value_id = :value_id'
    );

    /**
     * Initialize the the SQL statements.
     *
     * @param \TechDivision\Import\Utils\PrimaryKeyUtilInterface $primaryKeyUtil The primary key util instance
     */
    public function __construct(PrimaryKeyUtilInterface $primaryKeyUtil)
    {

        // call the parent constructor
        parent::__construct();

        // set the primary key util
        $this->primaryKeyUtil = $primaryKeyUtil;

        // merge the class statements
        foreach ($this->statements as $key => $statement) {
            $this->preparedStatements[$key] = $this->replacePrimaryKeyMemberName($statement);
        }
    }

    /**
     * Returns the SQL statement with the passed ID.
     *
     * @param string $id The ID of the SQL statement to return
     *
     * @return string The SQL statement
     * @throws \Exception Is thrown, if the SQL statement with the passed key cannot be found
     */
    public function load($id)
    {

        // try to find the SQL statement with the passed key
        if (isset($this->preparedStatements[$id])) {
            return $this->preparedStatements[$id];
        }

        // throw an exception if NOT available
        throw new \Exception(sprintf('Can\'t find SQL statement with ID %s', $id));
    }

    /**
     * Returns the primary key util instance.
     *
     * @return \TechDivision\Import\Utils\PrimaryKeyUtilInterface The primary key util instance
     */
    private function getPrimaryKeyUtil()
    {
        return $this->primaryKeyUtil;
    }

    /**
     * Replaces the PK member name in the passed SQL statement.
     *
     * @param string $statement The statement to replace the PK member name for
     *
     * @return string The statement with the replace PK member name
     */
    private function replacePrimaryKeyMemberName($statement)
    {
        return str_replace('${' . SqlStatementRepository::PK_MEMBER_NAME . '}', $this->getPrimaryKeyUtil()->getPrimaryKeyMemberName(), $statement);
    }
}
