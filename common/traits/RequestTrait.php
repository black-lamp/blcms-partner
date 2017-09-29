<?php
namespace bl\cms\partner\common\traits;

use bl\cms\partner\common\entities\ModerationStatus;

/**
 * Trait for [[\bl\cms\partner\common\entities\EmployeeRequest]]
 * and [[\bl\cms\partner\common\entities\UserRequest]] entities
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
trait RequestTrait
{
    use ModelStatusTrait;

    /**
     * Set status `On confirmation` for entity
     *
     * @param boolean $save
     */
    public function setStatusOnConfirmation($save = true)
    {
        $this->updateStatus(ModerationStatus::STATUS_ON_CONFIRMATION, $save);
    }

    /**
     * Set status `On moderation` for entity
     *
     * @param boolean $save
     */
    public function setStatusOnModeration($save = true)
    {
        $this->updateStatus(ModerationStatus::STATUS_ON_MODERATION, $save);
    }

    /**
     * Set status `Accepted` for entity
     *
     * @param boolean $save
     */
    public function setStatusAccepted($save = true)
    {
        $this->updateStatus(ModerationStatus::STATUS_ACCEPTED, $save);
    }

    /**
     * Set status `Decline` for entity
     *
     * @param boolean $save
     */
    public function setStatusDecline($save = true)
    {
        $this->updateStatus(ModerationStatus::STATUS_DECLINE, $save);
    }
}