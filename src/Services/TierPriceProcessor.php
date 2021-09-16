<?php

/**
 * TechDivision\Import\Product\TierPrice\Services\TierPriceProcessor
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

namespace TechDivision\Import\Product\TierPrice\Services;

use TechDivision\Import\Dbal\Actions\ActionInterface;
use TechDivision\Import\Dbal\Utils\PrimaryKeyUtilInterface;
use TechDivision\Import\Dbal\Connection\ConnectionInterface;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\Repositories\ProductRepositoryInterface;
use TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface;

/**
 * Default implementation of tier price processor objects, giving access to CRUD methods.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class TierPriceProcessor implements TierPriceProcessorInterface
{

    /**
     * A \PDO connection used to load data and handle CRUD functionality.
     *
     * @var \TechDivision\Import\Dbal\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * The primary key util instance.
     *
     * @var \TechDivision\Import\Dbal\Utils\PrimaryKeyUtilInterface
     */
    protected $primaryKeyUtil;

    /**
     * The action for tier price  CRUD methods.
     *
     * @var \TechDivision\Import\Dbal\Actions\ActionInterface
     */
    protected $tierPriceAction;

    /**
     * The repository to load the tier prices with.
     *
     * @var \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface
     */
    protected $tierPriceRepository;

    /**
     * Caches the existing tier price records referenced by their unique hash.
     *
     * @var array
     */
    protected $tierPricesByHash = null;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Dbal\Connection\ConnectionInterface                         $connection          The \PDO connnection instance
     * @param \TechDivision\Import\Dbal\Utils\PrimaryKeyUtilInterface                          $primaryKeyUtil      The primary key util
     * @param \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface $tierPriceRepository The repository to load the tier prices with
     * @param \TechDivision\Import\Product\Repositories\ProductRepositoryInterface             $productRepository   The repository to load the products with
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface                                $tierPriceAction     The action for tier price  CRUD methods
     */
    public function __construct(
        ConnectionInterface $connection,
        PrimaryKeyUtilInterface $primaryKeyUtil,
        TierPriceRepositoryInterface $tierPriceRepository,
        ProductRepositoryInterface $productRepository,
        ActionInterface $tierPriceAction
    ) {
        $this->setConnection($connection);
        $this->setPrimaryKeyUtil($primaryKeyUtil);
        $this->setTierPriceRepository($tierPriceRepository);
        $this->setProductRepository($productRepository);
        $this->setTierPriceAction($tierPriceAction);
    }

    /**
     * Set's the passed connection.
     *
     * @param \TechDivision\Import\Dbal\Connection\ConnectionInterface $connection The connection to set
     *
     * @return void
     */
    public function setConnection(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Return's the connection.
     *
     * @return \TechDivision\Import\Dbal\Connection\ConnectionInterface The connection instance
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Sets the passed primary key util instance.
     *
     * @param \TechDivision\Import\Dbal\Utils\PrimaryKeyUtilInterface $primaryKeyUtil The primary key util instance
     *
     * @return void
     */
    public function setPrimaryKeyUtil(PrimaryKeyUtilInterface $primaryKeyUtil)
    {
        $this->primaryKeyUtil = $primaryKeyUtil;
    }

    /**
     * Returns the primary key util instance.
     *
     * @return \TechDivision\Import\Dbal\Utils\PrimaryKeyUtilInterface The primary key util instance
     */
    public function getPrimaryKeyUtil()
    {
        return $this->primaryKeyUtil;
    }

    /**
     * Returns the primary key member name for the actual Magento edition.
     *
     * @return string The primary key member name
     * @see \TechDivision\Import\Dbal\Utils\PrimaryKeyUtilInterface::getPrimaryKeyMemberName()
     */
    public function getPrimaryKeyMemberName()
    {
        return $this->getPrimaryKeyUtil()->getPrimaryKeyMemberName();
    }

    /**
     * Turns off autocommit mode. While autocommit mode is turned off, changes made to the database via the PDO
     * object instance are not committed until you end the transaction by calling ProductProcessor::commit().
     * Calling ProductProcessor::rollBack() will roll back all changes to the database and return the connection
     * to autocommit mode.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.begintransaction.php
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * Commits a transaction, returning the database connection to autocommit mode until the next call to
     * ProductProcessor::beginTransaction() starts a new transaction.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.commit.php
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * Rolls back the current transaction, as initiated by ProductProcessor::beginTransaction().
     *
     * If the database was set to autocommit mode, this function will restore autocommit mode after it has
     * rolled back the transaction.
     *
     * Some databases, including MySQL, automatically issue an implicit COMMIT when a database definition
     * language (DDL) statement such as DROP TABLE or CREATE TABLE is issued within a transaction. The implicit
     * COMMIT will prevent you from rolling back any other changes within the transaction boundary.
     *
     * @return boolean Returns TRUE on success or FALSE on failure
     * @link http://php.net/manual/en/pdo.rollback.php
     */
    public function rollBack()
    {
        return $this->connection->rollBack();
    }

    /**
     * Sets the action with the tier price CRUD methods.
     *
     * @param \TechDivision\Import\Dbal\Actions\ActionInterface $tierPriceAction The action with the tier price CRUD methods
     *
     * @return void
     */
    public function setTierPriceAction(ActionInterface $tierPriceAction)
    {
        $this->tierPriceAction = $tierPriceAction;
    }

    /**
     * Returns the action with the tier price CRUD methods.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The action instance
     */
    public function getTierPriceAction()
    {
        return $this->tierPriceAction;
    }

    /**
     * Sets the repository to load the tier prices with.
     *
     * @param \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface $tierPriceRepository The repository instance
     *
     * @return void
     */
    public function setTierPriceRepository(TierPriceRepositoryInterface $tierPriceRepository)
    {
        $this->tierPriceRepository = $tierPriceRepository;
    }

    /**
     * Returns the repository to load the tier prices with.
     *
     * @return \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface The repository instance
     */
    public function getTierPriceRepository()
    {
        return $this->tierPriceRepository;
    }

    /**
     * Sets the repository to load the products with.
     *
     * @param \TechDivision\Import\Product\Repositories\ProductRepositoryInterface $productRepository The repository instance
     *
     * @return void
     */
    public function setProductRepository(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Returns the repository to load the products with.
     *
     * @return \TechDivision\Import\Product\Repositories\ProductRepositoryInterface The repository instance
     */
    public function getProductRepository()
    {
        return $this->productRepository;
    }

    /**
     * Load's and return's the product with the passed SKU.
     *
     * @param string $sku The SKU of the product to load
     *
     * @return array The product
     */
    public function loadProduct($sku)
    {
        return $this->getProductRepository()->findOneBySku($sku);
    }

    /**
     * Returns all tier prices.
     *
     * @return array The array with the tier prices
     */
    public function loadTierPrices()
    {
        return $this->getTierPriceRepository()->findAll();
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
    public function loadTierPriceByPkAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($pk, $allGroups, $customerGroupId, $qty, $websiteId)
    {
        return $this->getTierPriceRepository()->findOneByPkAndAllGroupsAndCustomerGroupIdAndQtyAndWebsiteId($pk, $allGroups, $customerGroupId, $qty, $websiteId);
    }

    /**
     * Persists the tier price with the passed data.
     *
     * @param array       $row  The tier price to persist
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted entity
     */
    public function persistTierPrice($row, $name = null)
    {
        return $this->getTierPriceAction()->persist($row, $name);
    }

    /**
     * Deletes the tierprice with the passed attributes.
     *
     * @param array       $row  The attributes of the entity to delete
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function deleteTierPrice($row, $name = null)
    {
        $this->getTierPriceAction()->delete($row, $name);
    }

    /**
     * Clean-Up the tier prices after an add-update operation.
     *
     * @param array $processedTierPrices The array with tier prices processed in the actual import
     * @param array $pkToSkuMapping      The array with PK => SKU mapping of products processed in the actual import
     *
     * @return void
     */
    public function cleanUpTierPrices(array $processedTierPrices, array $pkToSkuMapping)
    {

        // load ALL available tier prices
        $availableTierPrices = $this->loadTierPrices();

        // iterate over the tier prices and delete the obsolete ones
        foreach ($availableTierPrices as $tierPrice) {
            // if the tier price has been processed, continue
            if (isset($processedTierPrices[$tierPrice[MemberNames::VALUE_ID]])) {
                continue;
            }

            // if the product has been processed, delete the tier price
            if (isset($pkToSkuMapping[$tierPrice[$this->getPrimaryKeyMemberName()]])) {
                // delete the tier price, because the entity is part of the import but the tier price is NOT available any more
                $this->deleteTierPrice([MemberNames::VALUE_ID => $tierPrice[MemberNames::VALUE_ID]]);
            }
        }
    }
}
