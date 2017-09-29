<?php
namespace bl\cms\partner\common\entities;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "partner_offer_own_item".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $offer_id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property double $price
 * @property integer $number
 *
 * @property CommercialOffer $offer
 */
class PartnerOfferOwnItem extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_offer_own_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offer_id', 'number'], 'integer'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['title', 'image'], 'string', 'max' => 255],
            [['offer_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommercialOffer::className(), 'targetAttribute' => ['offer_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'offer_id' => Yii::t('partner.entity', 'Offer'),
            'title' => Yii::t('partner.entity', 'Title'),
            'description' => Yii::t('partner.entity', 'Description'),
            'image' => Yii::t('partner.entity', 'Image'),
            'price' => Yii::t('partner.entity', 'Price'),
            'number' => Yii::t('partner.entity', 'Number'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(CommercialOffer::className(), ['id' => 'offer_id']);
    }
}