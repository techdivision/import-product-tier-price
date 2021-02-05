<?php

/**
 * TechDivision\Import\Product\TierPrice\Actions\Processors\TierPriceCreateProcessor
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

namespace TechDivision\Import\Product\TierPrice\Actions\Processors;

use TechDivision\Import\Product\TierPrice\Utils\SqlStatementKeys;

/**
 * Responsible for inserting tier prices.
 *
 * @author     Klaas-Tido Rühl <kr@refusion.com>
 * @author     Tim Wagner <t.wagner@techdivision.com>
 * @copyright  2021 REFUSiON GmbH <info@refusion.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       https://github.com/techdivision/import-product-tier-price
 * @link       https://www.techdivision.com
 * @link       https://www.refusion.com
 * @deprecated Since 17.0.0
 * @see        \TechDivision\Import\Actions\Processors\GenericIdentifierProcessor
 */
class TierPriceCreateProcessor extends \TechDivision\Import\Actions\Processors\AbstractCreateProcessor
{

    /**
     * Return's the array with the SQL statements that has to be prepared.
     *
     * @return array The SQL statements to be prepared
     * @see \TechDivision\Import\Actions\Processors\AbstractBaseProcessor::getStatements()
     */
    protected function getStatements()
    {
        return array(SqlStatementKeys::CREATE_TIER_PRICE => $this->loadStatement(SqlStatementKeys::CREATE_TIER_PRICE));
    }

    /**
     * Persist's the passed row.
     *
     * @param array       $row                  The row to persist
     * @param string|null $name                 The name of the prepared statement that has to be executed
     * @param string|null $primaryKeyMemberName The primary key member name of the entity to use
     *
     * @return string The last inserted ID
     */
    public function execute($row, $name = null, $primaryKeyMemberName = null)
    {
        parent::execute($row, $name);
        return $this->getConnection()->lastInsertId();
    }
}
