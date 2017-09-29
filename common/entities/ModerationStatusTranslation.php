<?php
namespace bl\cms\partner\common\entities;

/**
 * This is the model class for "ModerationStatusTranslation".
 *
 * @property integer $id
 * @property integer $statusId
 * @property integer $languageId
 * @property string $title
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class ModerationStatusTranslation extends \yii2tech\filedb\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function fileName()
    {
        return 'ModerationStatusTranslation';
    }

    /**
     * @return \yii2tech\filedb\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(ModerationStatus::class, ['id' => 'statusId']);
    }
}