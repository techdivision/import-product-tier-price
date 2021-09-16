<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\MemberNames
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

namespace TechDivision\Import\Product\TierPrice\Utils;

/**
 * Member names specifically needed by the tier prices import.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   https://opensource.org/licenses/MIT
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class MemberNames extends \TechDivision\Import\Product\Utils\MemberNames
{

    /**
     * Name for the member 'all_groups'.
     *
     * @var string
     */
    const ALL_GROUPS = 'all_groups';

    /**
     * Name for the member 'customer_group_id'.
     *
     * @var string
     */
    const CUSTOMER_GROUP_ID = 'customer_group_id';

    /**
     * Name for the member 'percentage_value'.
     *
     * @var string
     */
    const PERCENTAGE_VALUE = 'percentage_value';
}
