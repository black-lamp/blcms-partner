<?php
namespace bl\cms\partner\common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;

use bl\cms\partner\common\db\EmployeeRequestQuery;
use bl\cms\partner\common\traits\RequestTrait;
use bl\cms\partner\common\interfaces\StatusInterface;

use bl\cms\shop\common\components\user\models\User;

/**
 * This is the model class for table "partner_employee_request".
 *
 * @property integer $id
 * @property integer $companyId
 * @property integer $userId
 * @property integer $statusId
 * @property integer $createdAt
 * @property integer $moderatedAt
 *
 * @property PartnerCompany $company
 * @property User $user
 * @property ModerationStatus $status
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class EmployeeRequest extends \yii\db\ActiveRecord implements StatusInterface
{
    use RequestTrait;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'moderatedAt',
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_employee_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createdAt', 'moderatedAt'], 'safe'],

            [['companyId'], 'required'],
            [['companyId'], 'integer'],

            [['userId'], 'required'],
            [['userId'], 'integer'],

            [['statusId'], 'integer'],
            [['statusId'], 'default', 'value' => ModerationStatus::STATUS_ON_MODERATION],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => Yii::t('partner.entity', 'ID'),
            'companyId'   => Yii::t('partner.entity', 'Company ID'),
            'userId'      => Yii::t('partner.entity', 'User ID'),
            'statusId'    => Yii::t('partner.entity', 'Status ID'),
            'createdAt'   => Yii::t('partner.entity', 'Created at'),
            'moderatedAt' => Yii::t('partner.entity', 'Moderated at'),
        ];
    }

    /**
     * @inheritdoc
     * @return EmployeeRequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EmployeeRequestQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(PartnerCompany::className(), ['id' => 'companyId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(ModerationStatus::class, ['id' => 'statusId']);
    }

    /**
     * @inheritdoc
     */
    public function statusCode()
    {
        return $this->statusId;
    }
}
