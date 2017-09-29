<?php
namespace bl\cms\partner\common\db;

use bl\cms\partner\common\traits\RequestQueryTrait;

/**
 * This is the ActiveQuery class for EmployeeRequest.
 *
 * @see EmployeeRequest
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class EmployeeRequestQuery extends \yii\db\ActiveQuery
{
    use RequestQueryTrait;
}
