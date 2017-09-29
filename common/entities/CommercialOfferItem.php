<?php
namespace bl\cms\partner\common\entities;

use Yii;

use yii2tech\ar\position\PositionBehavior;

/**
 * This is the model class for table "partner_offer_item".
 *
 * @property integer $id
 * @property integer $offerId
 * @property integer $productId
 * @property integer $combinationId
 * @property integer $quantity
 * @property integer $position
 *
 * @property CommercialOffer $offer
 * @property CommercialOfferItemAdditionalProduct[] $additionalProducts
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CommercialOfferItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'positionBehavior' => [
                'class' => PositionBehavior::class
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%partner_offer_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['offerId', 'productId'], 'required'],
            [['quantity'], 'default', 'value' => 1],
            [['offerId', 'productId', 'combinationId', 'quantity', 'position'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('partner.entity', 'ID'),
            'offerId'       => Yii::t('partner.entity', 'Offer ID'),
            'productId'     => Yii::t('partner.entity', 'Product ID'),
            'combinationId' => Yii::t('partner.entity', 'Combination ID'),
            'quantity'      => Yii::t('partner.entity', 'Quantity'),
            'position'      => Yii::t('partner.entity', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(CommercialOffer::className(), ['id' => 'offerId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalProducts()
    {
        return $this->hasMany(CommercialOfferItemAdditionalProduct::className(), ['partner_offer_item_id' => 'id']);
    }
    /**
     * @return boolean
     */
    public function hasAdditionalProducts()
    {
        $count = CommercialOfferItemAdditionalProduct::find()->where(['partner_offer_item_id' => $this->id])->count();
        return boolval($count);
    }
}
