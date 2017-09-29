<?php
namespace bl\cms\partner\common\entities;

use Yii;

use bl\cms\shop\common\components\user\models\User;

/**
 * This is the model class for table "partner_company_employee".
 *
 * @property integer $id
 * @property integer $companyId
 * @property integer $userId
 *
 * @property PartnerCompany $company
 * @property User $user
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyEmployee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_company_employee';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyId', 'userId'], 'required'],
            [['companyId', 'userId'], 'integer'],
            [['companyId'], 'exist', 'skipOnError' => true, 'targetClass' => PartnerCompany::className(), 'targetAttribute' => ['companyId' => 'id']],
            [['userId'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['userId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'        => Yii::t('partner.entity', 'ID'),
            'companyId' => Yii::t('partner.entity', 'Company ID'),
            'userId'    => Yii::t('partner.entity', 'User ID'),
        ];
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
}
