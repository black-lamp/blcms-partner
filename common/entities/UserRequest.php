<?php
namespace bl\cms\partner\common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;

use bl\cms\partner\common\db\UserRequestQuery;
use bl\cms\partner\common\traits\RequestTrait;
use bl\cms\partner\common\interfaces\StatusInterface;

use bl\cms\shop\common\components\user\models\User;

/**
 * This is the model class for table "partner_user_request".
 *
 * @property integer $id
 * @property integer $userId
 * @property integer $city
 * @property integer $statusId
 * @property string $companyName
 * @property string $officialCompanyName
 * @property string $site
 * @property integer $createdAt
 * @property integer $moderatedAt
 *
 * @property ModerationStatus $status
 * @property User $user
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class UserRequest extends \yii\db\ActiveRecord implements StatusInterface
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
                'updatedAtAttribute' => 'moderatedAt'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_user_request';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['statusId'], 'integer'],
            [['statusId'], 'default', 'value' => ModerationStatus::STATUS_ON_MODERATION],

            [['createdAt', 'moderatedAt'], 'integer'],
            [['createdAt', 'moderatedAt'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'                  => Yii::t('partner.entity', 'ID'),
            'userId'              => Yii::t('partner.entity', 'User ID'),
            'city'              => Yii::t('partner.entity', 'City'),
            'statusId'            => Yii::t('partner.entity', 'Status ID'),
            'companyName'         => Yii::t('partner.entity', 'Company name'),
            'officialCompanyName' => Yii::t('partner.entity', 'Official company name'),
            'site'                => Yii::t('partner.entity', 'Site link'),
            'createdAt'           => Yii::t('partner.entity', 'Created at'),
            'moderatedAt'         => Yii::t('partner.entity', 'Moderated at'),
        ];
    }

    /**
     * @inheritdoc
     * @return UserRequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserRequestQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(ModerationStatus::class, ['id' => 'statusId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }

    /**
     * @inheritdoc
     */
    public function statusCode()
    {
        return $this->statusId;
    }
}
