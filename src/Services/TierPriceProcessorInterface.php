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
     * Returns the action with the tier price CRUD methods.
     *
     * @return \TechDivision\Import\Actions\ActionInterface The action instance
     */
    public function getTierPriceAction();

    /**
     * Returns the repository to load the tier prices with.
     *
     * @return \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface The repository instance
     */
    public function getTierPriceRepository();

    /**
     * Returns the repository to load the products with.
     *
     * @return \TechDivision\Import\Product\Repositories\ProductRepositoryInterface The repository instance
     */
    public function getProductRepository();

    /**
     * Creates a hash from the tier price data that bijectively corresponds to the unique
     * constraint in the tier price table.
     *
     * @param array $tierPrice The tier price to create the hash for
     *
     * @return string The hash for the passed tier price
     */
    public function getTierPriceHash($tierPrice);

    /**
     * Returns all the existing tier prices referenced by their unique hash.
     *
     * @return array The array with the hashed tier prices
     */
    public function getTierPricesByHash();

    /**
     * Returns all tier prices.
     *
     * @return array The tier prices
     */
    public function getTierPrices();

    /**
     * Matches the given tier price to an existing one, if possible.
     *
     * @param array $tierPrice The tier price to match
     *
     * @return array|null Returns the matched tier price
     */
    public function matchTierPrice($tierPrice);

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
     * Indicates whether the two given tier prices are equal.
     *
     * @param array $tierPrice1 The first tier price to match
     * @param array $tierPrice2 The second tier price to match
     *
     * @return boolean TRUE if the passed tier prices are equal, else FALSE
     */
    public function tierPricesEqual($tierPrice1, $tierPrice2);
}
