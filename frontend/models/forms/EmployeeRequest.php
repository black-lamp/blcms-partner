<?php
namespace bl\cms\partner\frontend\models\forms;

use yii\base\Model;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\common\entities\EmployeeRequest as EmployeeRequestEntity;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class EmployeeRequest extends Model
{
    /**
     * @var integer
     */
    public $companyId;
    /**
     * @var integer
     */
    public $userId;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyId'], 'required'],
            [['companyId'], 'integer'],

            [['userId'], 'required'],
            [['userId'], 'integer'],
        ];
    }

    // TODO: PHPDoc
    public function send()
    {
        if (!$this->validate()) {
            return false;
        }

        $request = new EmployeeRequestEntity([
            'companyId' => $this->companyId,
            'userId' => $this->userId
        ]);
        $request->insert(false);

        if ($module = PartnerModule::getInstance()) {
            return $module->getMailer()->sendRequestEmployeeNotification(
                $this->companyId,
                $this->userId
            );
        }

        return false;
    }
}