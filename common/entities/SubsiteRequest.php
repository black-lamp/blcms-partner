<?php
namespace bl\cms\partner\common\entities;

use Yii;

/**
 * This is the model class for table "partner_subsite_request".
 *
 * @property integer $id
 * @property integer $companyId
 * @property string $domainName
 * @property integer $hasHosting
 *
 * @property PartnerCompany $company
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class SubsiteRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%partner_subsite_request}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('partner.entity', 'ID'),
            'companyId'  => Yii::t('partner.entity', 'Company ID'),
            'domainName' => Yii::t('partner.entity', 'Domain name'),
            'hasHosting' => Yii::t('partner.entity', 'Has hosting'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(PartnerCompany::className(), ['id' => 'companyId']);
    }
}
