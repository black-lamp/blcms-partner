<?php
namespace bl\cms\partner\common\entities;

use bl\multilang\behaviors\TranslationBehavior;
use Yii;

/**
 * This is the model class for table "partner_material_category".
 *
 * @property integer $id
 *
 * @property Material[] $materials
 * @property MaterialCategoryTranslation[] $translations
 * @property MaterialCategoryTranslation $translation
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class MaterialCategory extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::class,
                'translationClass' => MaterialCategoryTranslation::class,
                'relationColumn' => 'categoryId',
                'languageColumn' => 'languageId'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_material_category';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('partner.entity', 'ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterials()
    {
        return $this->hasMany(Material::className(), ['categoryId' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(MaterialCategoryTranslation::className(), ['categoryId' => 'id']);
    }
}
