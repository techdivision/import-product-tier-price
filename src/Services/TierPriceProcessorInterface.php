<?php

/**
 * TechDivision\Import\Product\TierPrice\Services\TierPriceProcessorInterface
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

namespace TechDivision\Import\Product\TierPrice\Services;

use TechDivision\Import\Product\Services\ProductProcessorInterface;

/**
 * Interface for tier price processor objects, giving access to CRUD methods.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
interface TierPriceProcessorInterface extends ProductProcessorInterface
{

    /**
     * Returns the primary key util instance.
     *
     * @return \TechDivision\Import\Utils\PrimaryKeyUtilInterface The primary key util instance
     */
    public function getPrimaryKeyUtil();

    /**
     * Returns the primary key member name for the actual Magento edition.
     *
     * @return string The primary key member name
     * @see \TechDivision\Import\Utils\PrimaryKeyUtilInterface::getPrimaryKeyMemberName()
     */
    public function getPrimaryKeyMemberName();

    /**
     * Returns the action with the tier price CRUD methods.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The action instance
     */
    public function getTierPriceAction();

    /**
     * Returns the repository to load the tier prices with.
     *
     * @return \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface The repository instance
     */
    public function getTierPriceRepository();

    /**
     * Returns all tier prices.
     *
     * @return array The array with the tier prices
     */
    public function loadTierPrices();

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
    public function loadTierPriceByPkAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($pk, $allGroups, $customerGroupId, $qty, $websiteId);

    /**
     * Persists the tier price with the passed data.
     *
     * @param array       $row  The tier price to persist
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted entity
     */
    public function persistTierPrice($row, $name = null);

    /**
     * Deletes the tierprice with the passed attributes.
     *
     * @param array       $row  The attributes of the entity to delete
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function deleteTierPrice($row, $name = null);

    /**
     * Clean-Up the tier prices after an add-update operation.
     *
     * @param array $processedTierPrices The array with tier prices processed in the actual import
     * @param array $pkToSkuMapping      The array with SKU => PK mapping of products processed in the actual import
     *
     * @return void
     */
    public function cleanUpTierPrices(array $processedTierPrices, array $pkToSkuMapping);
}
