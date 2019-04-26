<?php

/**
 * TechDivision\Import\Product\TierPrice\Utils\ConfigurationKeys
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link    https://github.com/techdivision/import-product-tier-price
 * @link    https://www.techdivision.com
 */

namespace TechDivision\Import\Product\TierPrice\Utils;

/**
 * Utility class containing the configuration keys.
 *
 * @author  Tim Wagner <t.wagner@techdivision.com>
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link    https://github.com/techdivision/import-product-tier-price
 * @link    https://www.techdivision.com
 */
class ConfigurationKeys extends \TechDivision\Import\Product\Utils\ConfigurationKeys
{

    /**
     * Name for the configuration key 'clean-up-tier-prices'.
     *
     * @var string
     */
    const CLEAN_UP_TIER_PRICES = 'clean-up-tier-prices';

    /**
     * Name for the configuration key 'customer-group-code-mappings'.
     *
     * @var string
     */
    const CUSTOMER_GROUP_CODE_MAPPINGS = 'customer-group-code-mappings';

    /**
     * Name for the configuration key 'website-code-mappings'.
     *
     * @var string
     */
    const WEBSITE_CODE_MAPPINGS = 'website-code-mappings';
}
