<?php
namespace bl\cms\partner\common\entities;

use Yii;

/**
 * This is the model class for table "partner_company_info_category".
 *
 * @property integer $id
 * @property string $key
 *
 * @property CompanyInfo[] $info
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyInfoCategory extends \yii\db\ActiveRecord
{
    const CATEGORY_EMAILS = 1;
    const CATEGORY_PHONES = 2;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_company_info_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'  => Yii::t('partner.entity', 'ID'),
            'key' => Yii::t('partner.entity', 'Key'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInfo()
    {
        return $this->hasMany(CompanyInfo::className(), ['categoryId' => 'id']);
    }
}
