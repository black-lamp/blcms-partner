<?php
namespace bl\cms\partner\common\entities;

use Yii;
use yii\behaviors\TimestampBehavior;

use bl\multilang\behaviors\TranslationBehavior;

/**
 * This is the model class for table "partner_material".
 *
 * @property integer $id
 * @property integer $categoryId
 * @property string $fileName
 * @property integer $createdAt
 * @property integer $updatedAt
 *
 * @property MaterialCategory $category
 * @property MaterialTranslation[] $translations
 * @property MaterialTranslation $translation
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class Material extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
            ],
            'translation' => [
                'class' => TranslationBehavior::class,
                'translationClass' => MaterialTranslation::class,
                'relationColumn' => 'materialId',
                'languageColumn' => 'languageId'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_material';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['categoryId'], 'required'],
            [['categoryId'], 'integer'],

            [['fileName'], 'required'],
            [['fileName'], 'string', 'max' => 500],

            [['createdAt', 'updatedAt'], 'safe'],
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
            'fileName'   => Yii::t('partner.entity', 'File name'),
            'createdAt'  => Yii::t('partner.entity', 'Created at'),
            'updatedAt'  => Yii::t('partner.entity', 'Updated at'),
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
    public function getTranslations()
    {
        return $this->hasMany(MaterialTranslation::className(), ['materialId' => 'id']);
    }
}
