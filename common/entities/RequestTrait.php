<?php
namespace bl\cms\partner\common\entities;

/**
 * Trait for [[EmployeeRequest]] and [[UserRequest]] entities
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
trait RequestTrait
{
    /**
     * Update entity status in database
     *
     * @param integer $status status ID
     * @param boolean $save if set `true` data will be save to database
     */
    private function updateStatus($status, $save) {
        $this->statusId = $status;

        if ($save) {
            $this->update(false);
        }
    }

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
}