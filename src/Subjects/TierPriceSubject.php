<?php

/**
 * TechDivision\Import\Product\TierPrice\Subjects\TierPriceSubject
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

namespace TechDivision\Import\Product\TierPrice\Subjects;

use League\Event\EmitterInterface;
use Doctrine\Common\Collections\Collection;
use TechDivision\Import\Product\TierPrice\Utils\MemberNames;
use TechDivision\Import\Product\Subjects\AbstractProductSubject;
use TechDivision\Import\Services\RegistryProcessorInterface;
use TechDivision\Import\Utils\Generators\GeneratorInterface;
use TechDivision\Import\Product\TierPrice\Repositories\CustomerGroupRepositoryInterface;

/**
 * Subject for processing tier prices.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class TierPriceSubject extends AbstractProductSubject
{

    /**
     * tier price value type: Fixed
     *
     * @var string
     */
    const VALUE_TYPE_FIXED = 'Fixed';

    /**
     * tier price value type: Discount
     *
     * @var string
     */
    const VALUE_TYPE_DISCOUNT = 'Discount';

    /**
     * tier price website code: All Websites
     *
     * @var string
     */
    const WEBSITE_CODE_ALL_WEBSITES = 'All Websites';

    /**
     * tier price website ID: All Websites
     *
     * @var int
     */
    const WEBSITE_ID_ALL_WEBSITES = 0;

    /**
     * tier price customer group code: ALL GROUPS
     *
     * @var string
     */
    const CUSTOMER_GROUP_CODE_ALL_GROUPS = 'ALL GROUPS';

    /**
     * tier price customer group ID: ALL GROUPS
     *
     * @var int
     */
    const CUSTOMER_GROUP_ID_ALL_GROUPS = 0;

    /**
     * The repository to load the customer groups with.
     *
     * @var \TechDivision\Import\Product\TierPrice\Repositories\CustomerGroupRepositoryInterface
     */
    protected $customerGroupRepository;

    /**
     * Holds the array mapping product row IDs to SKUs.
     *
     * @var array
     */
    protected $productSkusByRowId = array();

    /**
     * Caches the existing customer groups.
     *
     * @var array
     */
    protected $customerGroups = null;

    /**
     * Initializes the subject with the necessary instances.
     *
     * @param \TechDivision\Import\Services\RegistryProcessorInterface                             $registryProcessor          The registry processor instance
     * @param \TechDivision\Import\Utils\Generators\GeneratorInterface                             $coreConfigDataUidGenerator The UID generator for the core config data
     * @param \Doctrine\Common\Collections\Collection                                              $systemLoggers              The array with the system loggers instances
     * @param \League\Event\EmitterInterface                                                       $emitter                    The event emitter instance
     * @param \TechDivision\Import\Product\TierPrice\Repositories\CustomerGroupRepositoryInterface $customerGroupRepository    The repository to load the customer groups with
     */
    public function __construct(
        RegistryProcessorInterface $registryProcessor,
        GeneratorInterface $coreConfigDataUidGenerator,
        Collection $systemLoggers,
        EmitterInterface $emitter,
        CustomerGroupRepositoryInterface $customerGroupRepository
    ) {

        // invoke the parent instance3
        parent::__construct($registryProcessor, $coreConfigDataUidGenerator, $systemLoggers, $emitter);

        // set the customer group repository instance
        $this->customerGroupRepository = $customerGroupRepository;
    }

    /**
     * Intializes the previously loaded global data for exactly one variants.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function setUp($serial)
    {

        // invoke the parent method
        parent::setUp($serial);

        // load the entity manager and the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // load the status of the actual import process
        $status = $registryProcessor->getAttribute($serial);

        // load the attribute set we've prepared intially
        $this->productSkusByRowId = array_flip($status[\TechDivision\Import\Utils\RegistryKeys::SKU_ROW_ID_MAPPING]);
    }

    /**
     * Returns the repository to load the customer groups with.
     *
     * @return \TechDivision\Import\Product\TierPrice\Repositories\CustomerGroupRepositoryInterface The repository instance
     */
    public function getCustomerGroupRepository()
    {
        return $this->customerGroupRepository;
    }

    /**
     * Returns all customer groups
     *
     * @return array
     */
    public function getCustomerGroups()
    {

        // query whether or not the customer groups has already been initialized
        if ($this->customerGroups == null) {
            $this->customerGroups = $this->getCustomerGroupRepository()->findAll();
        }

        // return the customer groups
        return $this->customerGroups;
    }

    /**
     * Returns the customer group ID for the given code, if it exists.
     *
     * @param string $code The code of the requested customer group
     *
     * @return integer|null The ID of the customer group
     */
    public function getCustomerGroupIdForCode($code)
    {

        // load the customer groups
        $customerGroups = $this->getCustomerGroups();

        // query whether or not the customer group with the passed code is available
        if (isset($customerGroups[$code])) {
            return $customerGroups[$code][MemberNames::CUSTOMER_GROUP_ID];
        }

        // return NULL otherwise
        return;
    }

    /**
     * Indicates whether the product with the given row ID has been processed in the import.
     *
     * @param integer $rowId The row ID to query for for
     *
     * @return boolean TRUE if the product has been processed, else FALSE
     */
    public function rowIdHasBeenProcessed($rowId)
    {
        return isset($this->productSkusByRowId[$rowId]);
    }
}
