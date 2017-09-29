<?php
namespace bl\cms\partner\common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;

use common\entities\City;
use bl\cms\partner\common\traits\ModelStatusTrait;

use bl\cms\shop\common\components\user\models\User;

/**
 * This is the model class for table "partner_company".
 *
 * @property integer $id
 * @property integer $statusId
 * @property integer $ownerId
 * @property string $name
 * @property string $officialName
 * @property string $logo
 * @property string $siteLink
 * @property integer $city
 * @property integer $createdAt
 * @property integer $updatedAt
 *
 * @property CompanyInfo[] $info
 * @property User $owner
 * @property CompanyEmployee[] $employees
 * @property CompanyStatus $status
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class PartnerCompany extends \yii\db\ActiveRecord
{
    use ModelStatusTrait;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_company';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt'], 'safe'],
            [['ownerId'], 'required'],
            [['ownerId', 'statusId'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['city'], 'string', 'max' => 255],
            [['officialName'], 'string', 'max' => 500],
            [['statusId'], 'default', 'value' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('partner.entity', 'ID'),
            'statusId'      => Yii::t('partner.entity', 'Status ID'),
            'ownerId'       => Yii::t('partner.entity', 'Owner ID'),
            'name'          => Yii::t('partner.entity', 'Name'),
            'officialName'  => Yii::t('partner.entity', 'Official name'),
            'logo'          => Yii::t('partner.entity', 'Logo'),
            'siteLink'      => Yii::t('partner.entity', 'Site Link'),
            'city'        => Yii::t('partner.entity', 'City'),
            'createdAt'     => Yii::t('partner.entity', 'Created At'),
            'updatedAt'     => Yii::t('partner.entity', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasMany(CompanyInfo::className(), ['companyId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'ownerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(CompanyEmployee::className(), ['companyId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(CompanyStatus::class, ['id' => 'statusId']);
    }

    /**
     * Ban company
     *
     * @param boolean $save
     */
    public function ban($save = true)
    {
        $this->updateStatus(CompanyStatus::STATUS_BLOCKED, $save);
    }

    /**
     * Un ban company
     *
     * @param boolean $save
     */
    public function unBan($save = true)
    {
        $this->updateStatus(CompanyStatus::STATUS_ACTIVE, $save);
    }
}
