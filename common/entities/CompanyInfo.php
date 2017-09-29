<?php
namespace bl\cms\partner\common\entities;

use Yii;

/**
 * This is the model class for table "partner_company_info".
 *
 * @property integer $id
 * @property integer $categoryId
 * @property integer $companyId
 * @property string $content
 *
 * @property CompanyInfoCategory $category
 * @property PartnerCompany $company
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyInfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_company_info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryId', 'companyId', 'content'], 'required'],
            [['categoryId', 'companyId'], 'integer'],
            [['content'], 'string'],
            [['categoryId'], 'exist', 'skipOnError' => true, 'targetClass' => CompanyInfoCategory::className(), 'targetAttribute' => ['categoryId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('partner.entity', 'ID'),
            'categoryId' => Yii::t('partner.entity', 'Category ID'),
            'companyId' => Yii::t('partner.entity', 'Company ID'),
            'content'    => Yii::t('partner.entity', 'Content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(CompanyInfoCategory::className(), ['id' => 'categoryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(PartnerCompany::className(), ['id' => 'companyId']);
    }

    /**
     * Return company emails
     *
     * @param integer $companyId
     * @return static[]
     */
    public static function getEmails($companyId)
    {
        return static::findAll([
            'categoryId' => CompanyInfoCategory::CATEGORY_EMAILS,
            'companyId' => $companyId
        ]);
    }

    /**
     * Return company phones
     *
     * @param integer $companyId
     * @return CompanyInfo[]
     */
    public static function getPhones($companyId)
    {
        return static::findAll([
            'categoryId' => CompanyInfoCategory::CATEGORY_PHONES,
            'companyId' => $companyId
        ]);
    }
}
