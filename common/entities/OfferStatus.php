<?php
namespace bl\cms\partner\common\entities;
use bl\multilang\behaviors\TranslationBehavior;

/**
 * This is the model class for "OfferStatus".
 *
 * @property integer $id
 * @property string $color
 *
 * @property OfferStatusTranslation $translation
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class OfferStatus extends \yii2tech\filedb\ActiveRecord
{
    const STATUS_UNDEFINED      = 1;
    const STATUS_ON_RESERVATION = 2;
    const STATUS_ORDERING       = 3;
    const STATUS_SENT_TO_CLIENT = 4;

    const STATUS_RESERVATION_ACCEPTED = 5;
    const STATUS_ORDER_ACCEPTED       = 6;
    const STATUS_ORDER_COMPLETED       = 7;
    const STATUS_CANCELED       = 10;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'translation' => [
                'class' => TranslationBehavior::class,
                'translationClass' => OfferStatusTranslation::class,
                'relationColumn' => 'statusId',
                'languageColumn' => 'languageId'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function fileName()
    {
        return 'OfferStatus';
    }

    /**
     * @return \yii2tech\filedb\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(OfferStatusTranslation::class, ['statusId' => 'id']);
    }
}