<?php

/**
 * TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepository
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

use TechDivision\Import\Repositories\AbstractRepository;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\TierPrice\Utils\SqlStatementKeys;

/**
 * Default implementation of repository for accessing tier price data.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class TierPriceRepository extends AbstractRepository implements TierPriceRepositoryInterface
{

    /**
     * The prepared statement to load the existing tier prices.
     *
     * @var \PDOStatement
     */
    protected $tierPricesStmt;

    /**
     * The prepared statement to load an existing tier price by PK, all groups, customer group ID, qty and website ID.
     *
     * @var \PDOStatement
     */
    protected $tierPriceByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteIdStmt;

    /**
     * Initializes the repository's prepared statements.
     *
     * @return void
     */
    public function init()
    {
        // initialize the prepared statements
        $this->tierPricesStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::TIER_PRICES));
        $this->tierPriceByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteIdStmt =
            $this->getConnection()->prepare($this->loadStatement(SqlStatementKeys::TIER_PRICE_BY_ENTITY_ID_AND_ALL_GROUPS_AND_CUSTOMER_GROUP_ID_AND_QTY_AND_WEBSITE_ID));
    }

    /**
     * Returns the tier price with the given parameters.
     *
     * @param string  $entityId        The entity ID of the product relation
     * @param integer $allGroups       The flag if all groups are affected or not
     * @param integer $customerGroupId The customer group ID
     * @param integer $qty             The tier price quantity
     * @param integer $websiteId       The website ID the tier price is related to
     *
     * @return array The tier price
     */
    public function findOneByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($entityId, $allGroups, $customerGroupId, $qty, $websiteId)
    {

        // initialize the params
        $params = array(
            MemberNames::ENTITY_ID         => $entityId,
            MemberNames::ALL_GROUPS        => $allGroups,
            MemberNames::CUSTOMER_GROUP_ID => $customerGroupId,
            MemberNames::QTY               => $qty,
            MemberNames::WEBSITE_ID        => $websiteId
        );

        // load and return the tier price with the passed params
        $this->tierPriceByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteIdStmt->execute($params);
        return $this->tierPriceByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteIdStmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns an array with all the tier prices.
     *
     * @return array The tier prices
     */
    public function findAll()
    {
        // load and return the tier prices
        $this->tierPricesStmt->execute();
        return $this->tierPricesStmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
