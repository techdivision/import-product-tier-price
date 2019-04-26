<?php

/**
 * TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface
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

/**
 * Interface for repository for accessing tier price data.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
interface TierPriceRepositoryInterface
{

    /**
     * Returns an array with all the tier prices.
     *
     * @return array The tier prices
     */
    public function findAll();

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
    public function findOneByEntityIdAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($entityId, $allGroups, $customerGroupId, $qty, $websiteId);
}
