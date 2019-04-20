<?php

/**
 * TechDivision\Import\Product\TierPrice\Services\TierPriceProcessor
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

use TechDivision\Import\Actions\ActionInterface;
use TechDivision\Import\Connection\ConnectionInterface;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\Repositories\ProductRepositoryInterface;
use TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface;

/**
 * Default implementation of tier price processor objects, giving access to CRUD methods.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class TierPriceProcessor implements TierPriceProcessorInterface
{

    /**
     * A \PDO connection used to load data and handle CRUD functionality.
     *
     * @var \TechDivision\Import\Connection\ConnectionInterface
     */
    protected $connection;

    /**
     * The action for tier price  CRUD methods.
     *
     * @var \TechDivision\Import\Actions\ActionInterface
     */
    protected $tierPriceAction;

    /**
     * The repository to load the tier prices with.
     *
     * @var \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface
     */
    protected $tierPriceRepository;

    /**
     * The repository to load the products with.
     *
     * @var \TechDivision\Import\Product\Repositories\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Caches the existing tier price records referenced by their unique hash.
     *
     * @var array
     */
    protected $tierPricesByHash = null;

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface                              $connection          The \PDO connnection instance
     * @param \TechDivision\Import\Product\TierPrice\Repositories\TierPriceRepositoryInterface $tierPriceRepository The repository to load the tier prices with
     * @param \TechDivision\Import\Product\Repositories\ProductRepositoryInterface             $productRepository   The repository to load the products with
     * @param \TechDivision\Import\Actions\ActionInterface                                     $tierPriceAction     The action for tier price  CRUD methods
     */
    public function __construct(
        ConnectionInterface $connection,
        TierPriceRepositoryInterface $tierPriceRepository,
        ProductRepositoryInterface $productRepository,
        ActionInterface $tierPriceAction
    ) {
        $this->setConnection($connection);
        $this->setTierPriceRepository($tierPriceRepository);
        $this->setProductRepository($productRepository);
        $this->setTierPriceAction($tierPriceAction);
    }

    /**
     * Set's the passed connection.
     *
     * @param \TechDivision\Import\Connection\ConnectionInterface $connection The connection to set
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
     * @return \TechDivision\Import\Connection\ConnectionInterface The connection instance
     */
    public function getConnection()
    {
        return $this->connection;
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
     * @param \TechDivision\Import\Actions\ActionInterface $tierPriceAction The action with the tier price CRUD methods
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
     * @return \TechDivision\Import\Actions\ActionInterface The action instance
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
     * Creates a hash from the tier price data that bijectively corresponds to the unique
     * constraint in the tier price table.
     *
     * @param array $tierPrice The tier price to create the hash for
     *
     * @return string The hash for the passed tier price
     */
    public function getTierPriceHash($tierPrice)
    {
        return sprintf(
            '%d-%d-%d-%.4F-%d',
            $tierPrice[$this->getProductRepository()->getPrimaryKeyName()],
            $tierPrice[MemberNames::ALL_GROUPS],
            $tierPrice[MemberNames::CUSTOMER_GROUP_ID],
            $tierPrice[MemberNames::QTY],
            $tierPrice[MemberNames::WEBSITE_ID]
        );
    }

    /**
     * Returns all the existing tier prices referenced by their unique hash.
     *
     * @return array The array with the hashed tier prices
     */
    public function getTierPricesByHash()
    {

        // query whether or not the tier prices has already been loaded
        if (is_null($this->tierPricesByHash)) {
            // initialize the array for the tier prices
            $result = [];
            // load ALL tier prices from the database
            $tierPrices = $this->getTierPriceRepository()->findAll();

            // register the tier prices with it's unique hash
            foreach ($tierPrices as $tierPrice) {
                $result[$this->getTierPriceHash($tierPrice)] = $tierPrice;
            }

            // set the array with the hashed tier prices
            $this->tierPricesByHash = $result;
        }

        // return the hashed tier prices
        return $this->tierPricesByHash;
    }

    /**
     * Returns all tier prices.
     *
     * @return array The tier prices
     */
    public function getTierPrices()
    {
        return array_values($this->getTierPricesByHash());
    }

    /**
     * Matches the given tier price to an existing one, if possible.
     *
     * @param array $tierPrice The tier price to match
     *
     * @return array|null Returns the matched tier price
     */
    public function matchTierPrice($tierPrice)
    {

        // load the tier prices
        $tierPricesByHash = $this->getTierPricesByHash();

        // query whether or not the passed one is available and return it, if possible
        return $tierPricesByHash[$this->getTierPriceHash($tierPrice)] ? $tierPricesByHash[$this->getTierPriceHash($tierPrice)] : null;
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
     * Indicates whether the two given tier prices are equal.
     *
     * @param array $tierPrice1 The first tier price to match
     * @param array $tierPrice2 The second tier price to match
     *
     * @return boolean TRUE if the passed tier prices are equal, else FALSE
     */
    public function tierPricesEqual($tierPrice1, $tierPrice2)
    {

        // load the product's primary key name
        $productPrimaryKeyName = $this->getProductRepository()->getPrimaryKeyName();

        // query whether or not the two given tier prices are equal
        return ($tierPrice1[$productPrimaryKeyName]         == $tierPrice2[$productPrimaryKeyName]) &&
               ($tierPrice1[MemberNames::VALUE]             == $tierPrice2[MemberNames::VALUE]) &&
               ($tierPrice1[MemberNames::PERCENTAGE_VALUE]  == $tierPrice2[MemberNames::PERCENTAGE_VALUE]) &&
               ($tierPrice1[MemberNames::QTY]               == $tierPrice2[MemberNames::QTY]) &&
               ($tierPrice1[MemberNames::ALL_GROUPS]        == $tierPrice2[MemberNames::ALL_GROUPS]) &&
               ($tierPrice1[MemberNames::CUSTOMER_GROUP_ID] == $tierPrice2[MemberNames::CUSTOMER_GROUP_ID]) &&
               ($tierPrice1[MemberNames::WEBSITE_ID]        == $tierPrice2[MemberNames::WEBSITE_ID]);
    }
}
