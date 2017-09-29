<?php
namespace bl\cms\partner\common\entities;

/**
 * This is the model class for "ModerationStatus".
 *
 * @property integer $id
 * @property ModerationStatusTranslation['] $translations
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class ModerationStatus extends \yii2tech\filedb\ActiveRecord
{
    const STATUS_ON_CONFIRMATION = 1;
    const STATUS_ON_MODERATION   = 2;
    const STATUS_ACCEPTED        = 3;
    const STATUS_DECLINE         = 4;


    /**
     * @inheritdoc
     */
    public static function fileName()
    {
        return 'ModerationStatus';
    }

    /**
     * @return \yii2tech\filedb\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(ModerationStatus::class, ['statusId' => 'id']);
    }
}