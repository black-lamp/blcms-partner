<?php
namespace bl\cms\partner\common\db;

use bl\cms\partner\common\entities\ModerationStatus;

/**
 * Trait for [[UserRequestQuery]] and [[EmployeeRequest query]]
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
trait RequestQueryTrait
{
    /**
     * Get record with status `On confirmation`
     *
     * @return mixed
     */
    public function onConfirmation()
    {
        return $this->andWhere(['statusId' => ModerationStatus::STATUS_ON_CONFIRMATION]);
    }

    /**
     * Get record with status `On moderation`
     *
     * @return mixed
     */
    public function onModeration()
    {
        return $this->andWhere(['statusId' => ModerationStatus::STATUS_ON_MODERATION]);
    }

    /**
     * Get record with status `Accepted`
     *
     * @return mixed
     */
    public function accepted()
    {
        return $this->andWhere(['statusId' => ModerationStatus::STATUS_ACCEPTED]);
    }
}