<?php
namespace bl\cms\partner\common\entities;

/**
 * This is the model class for "OfferStatusTranslation".
 *
 * @property integer $id
 * @property integer $statusId
 * @property integer $languageId
 * @property string $title
 * @property string $description
 * @property string $actionText
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class OfferStatusTranslation extends \yii2tech\filedb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function fileName()
    {
        return 'OfferStatusTranslation';
    }

    /**
     * @return \yii2tech\filedb\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(ModerationStatus::class, ['id' => 'statusId']);
    }
}