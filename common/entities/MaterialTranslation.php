<?php
namespace bl\cms\partner\common\entities;

use Yii;

use bl\multilang\entities\Language;

/**
 * This is the model class for table "partner_material_translation".
 *
 * @property integer $id
 * @property integer $materialId
 * @property integer $languageId
 * @property string $title
 *
 * @property Language $language
 * @property Material $material
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class MaterialTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_material_translation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materialId', 'languageId', 'title'], 'required'],
            [['materialId', 'languageId'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('partner.entity', 'ID'),
            'materialId' => Yii::t('partner.entity', 'Material ID'),
            'languageId' => Yii::t('partner.entity', 'Language ID'),
            'title'      => Yii::t('partner.entity', 'Title'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLanguage()
    {
        return $this->hasOne(Language::className(), ['id' => 'languageId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterial()
    {
        return $this->hasOne(Material::className(), ['id' => 'materialId']);
    }
}
