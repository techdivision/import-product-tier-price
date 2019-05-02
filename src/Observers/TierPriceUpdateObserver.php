<?php

/**
 * TechDivision\Import\Product\TierPrice\Observers\TierPriceUpdateObserver
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

namespace TechDivision\Import\Product\TierPrice\Observers;

use TechDivision\Import\Product\TierPrice\Utils\MemberNames;

/**
 * Observer for creating/updating/deleting tier prices from the database.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class TierPriceUpdateObserver extends TierPriceObserver
{

    /**
     * Initialize the product website with the passed attributes and returns an instance.
     *
     * @param array $attr The product website attributes
     *
     * @return array The initialized product website
     * @throws \RuntimeException Is thrown, if the attributes can not be initialized
     */
    protected function initializeTierPrice(array $attr)
    {

        // load the unique key parameters
        $pk = $attr[$this->getPrimaryKeyMemberName()];
        $qty = $attr[MemberNames::QTY];
        $allGroups = $attr[MemberNames::ALL_GROUPS];
        $websiteId = $attr[MemberNames::WEBSITE_ID];
        $customerGroupId = $attr[MemberNames::CUSTOMER_GROUP_ID];

        // try load the product by the given SKU
        if ($entity = $this->loadTierPriceByPkAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($pk, $allGroups, $customerGroupId, $qty, $websiteId)) {
            return $this->mergeEntity($entity, $attr);
        }

        // return the the attributes
        return $attr;
    }

    /**
     * Returns the tier price with the given parameters.
     *
     * @param string  $pk              The PK of the product relation
     * @param integer $allGroups       The flag if all groups are affected or not
     * @param integer $customerGroupId The customer group ID
     * @param integer $qty             The tier price quantity
     * @param integer $websiteId       The website ID the tier price is related to
     *
     * @return array The tier price
     */
    protected function loadTierPriceByPkAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($pk, $allGroups, $customerGroupId, $qty, $websiteId)
    {
        return $this->getTierPriceProcessor()->loadTierPriceByPkAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($pk, $allGroups, $customerGroupId, $qty, $websiteId);
    }
}
