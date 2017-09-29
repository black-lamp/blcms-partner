<?php
namespace bl\cms\partner\common\db;

use bl\cms\partner\common\traits\RequestQueryTrait;

/**
 * This is the ActiveQuery class for UserRequest.
 *
 * @see UserRequest
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class UserRequestQuery extends \yii\db\ActiveQuery
{
    use RequestQueryTrait;

    // TODO: PHPDoc
    public function status($companyId)
    {
        return $this->select('statusId')
            ->where(['id' => $companyId])
            ->scalar();
    }

    // TODO: PHPDoc
    public function withEmployees()
    {
        return $this->with('employees');
    }
}
