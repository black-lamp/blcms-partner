<?php
namespace bl\cms\partner\common\traits;

use bl\cms\partner\common\entities\OfferStatus;

/**
 * Trait for [[\bl\cms\partner\common\entities\OfferStatus]] entity
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
trait OfferTrait
{
    use ModelStatusTrait;

    /**
     * Set status `Undefined` for entity
     *
     * @param boolean $save
     */
    public function setStatusUndefined($save = true)
    {
        $this->updateStatus(OfferStatus::STATUS_UNDEFINED, $save);
    }

    /**
     * Set status `On reservation` for entity
     *
     * @param boolean $save
     */
    public function setStatusOnReservation($save = true)
    {
        $this->updateStatus(OfferStatus::STATUS_ON_RESERVATION, $save);
    }

    /**
     * Set status `Ordering` for entity
     *
     * @param boolean $save
     */
    public function setStatusOrdering($save = true)
    {
        $this->updateStatus(OfferStatus::STATUS_ORDERING, $save);
    }

    /**
     * Set status `Sent to client` for entity
     *
     * @param boolean $save
     */
    public function setStatusSentToClient($save = true)
    {
        $this->updateStatus(OfferStatus::STATUS_SENT_TO_CLIENT, $save);
    }
}