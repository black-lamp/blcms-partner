<?php
namespace bl\cms\partner\common\entities;

use Yii;

use bl\multilang\entities\Language;

/**
 * This is the model class for table "partner_material_category_translation".
 *
 * @property integer $id
 * @property integer $categoryId
 * @property integer $languageId
 * @property string $title
 *
 * @property MaterialCategory $category
 * @property Language $language
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class MaterialCategoryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_material_category_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryId'], 'required'],
            [['categoryId'], 'integer'],

            [['languageId'], 'required'],
            [['languageId'], 'integer'],

            [['title'], 'required'],
            [['title'], 'string', 'max' => 255]
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
            'languageId' => Yii::t('partner.entity', 'Language'),
            'title'      => Yii::t('partner.entity', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(MaterialCategory::className(), ['id' => 'categoryId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languageId']);
    }
}
