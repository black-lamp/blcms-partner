<?php
namespace bl\cms\partner\common\entities;

use bl\multilang\behaviors\TranslationBehavior;

/**
 * This is the model class for "CompanyStatus".
 *
 * @property integer $id
 * @property CompanyStatusTranslation[] $translations
 * @property CompanyStatusTranslation $translation
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyStatus extends \yii2tech\filedb\ActiveRecord
{
    const STATUS_ACTIVE  = 1;
    const STATUS_BLOCKED = 2;

    /**
     * @inheritdoc
     */
    /*public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::class,
                'translationClass' => CompanyStatusTranslation::class,
                'relationColumn' => 'statusId',
                'languageColumn' => 'languageId'
            ]
        ];
    }*/

    /**
     * @inheritdoc
     */
    public static function fileName()
    {
        return 'CompanyStatus';
    }

    /**
     * @return \yii2tech\filedb\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(CompanyStatusTranslation::class, ['statusId', 'id']);
    }
}