<?php

/**
 * TechDivision\Import\Product\TierPrice\Listeners\DeleteObsoleteTierPricesListener
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

namespace TechDivision\Import\Product\TierPrice\Listeners;

use TechDivision\Import\Subjects\SubjectInterface;
use TechDivision\Import\Product\TierPrice\Observers\TierPriceUpdateObserver;

/**
 * After the subject has finished it's processing, this listener causes the obsolete tier prices to be deleted.
 *
 * @author    Klaas-Tido Rühl <kr@refusion.com>
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2019 REFUSiON GmbH <info@refusion.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-tier-price
 * @link      https://www.techdivision.com
 * @link      https://www.refusion.com
 */
class DeleteObsoleteTierPricesListener extends \League\Event\AbstractListener
{

    /**
     * The invoking observer instance.
     *
     * @var \TechDivision\Import\Product\TierPrice\Observers\TierPriceUpdateObserver
     */
    protected $tierPriceUpdateObserver;

    /**
     * Initializes the listener with the invoking observer instance.
     *
     * @param \TechDivision\Import\Product\TierPrice\Observers\TierPriceUpdateObserver $tierPriceUpdateObserver The observer instance
     */
    public function __construct(TierPriceUpdateObserver $tierPriceUpdateObserver)
    {
        $this->tierPriceUpdateObserver = $tierPriceUpdateObserver;
    }

    /**
     * Handle the event.
     *
     * @param \League\Event\EventInterface                   $event   The event that triggered the listener
     * @param \TechDivision\Import\Subjects\SubjectInterface $subject The subject that triggered the listener
     *
     * @return void
     */
    public function handle(\League\Event\EventInterface $event, SubjectInterface $subject = null)
    {

        // remove the obsolete tier prices, if the subjects match
        if ($subject === $this->tierPriceUpdateObserver->getSubject()) {
            $this->tierPriceUpdateObserver->deleteObsoleteTierPrices();
        }
    }
}
