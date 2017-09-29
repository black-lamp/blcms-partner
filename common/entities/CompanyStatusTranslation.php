<?php
namespace bl\cms\partner\common\entities;

/**
 * This is the model class for "CompanyStatusTranslation".
 *
 * @property integer $id
 * @property integer $statusId
 * @property integer $languageId
 * @property string $title
 *
 * @property CompanyStatus $status
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyStatusTranslation extends \yii2tech\filedb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function fileName()
    {
        return 'CompanyStatusTranslation';
    }

    /**
     * @return \yii2tech\filedb\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(CompanyStatus::class, ['id', 'statusId']);
    }
}