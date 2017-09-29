<?php
namespace bl\cms\partner\common\entities;

use bl\cms\shop\common\entities\Product;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "partner_offer_item_additional_product".
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @property integer $id
 * @property integer $partner_offer_item_id
 * @property integer $additional_product_id
 * @property integer $number
 *
 * @property CommercialOfferItem $partnerOfferItem
 * @property Product $product
 */
class CommercialOfferItemAdditionalProduct extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'partner_offer_item_additional_product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['partner_offer_item_id', 'additional_product_id', 'number'], 'integer'],
            [['partner_offer_item_id'], 'exist', 'skipOnError' => true, 'targetClass' => CommercialOfferItem::className(), 'targetAttribute' => ['partner_offer_item_id' => 'id']],
            [['additional_product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['additional_product_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'partner_offer_item_id' => 'Partner Offer Item ID',
            'additional_product_id' => 'Additional Product ID',
            'number' => 'Number',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCommercialOfferItem()
    {
        return $this->hasOne(CommercialOfferItem::className(), ['id' => 'partner_offer_item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'additional_product_id']);
    }
}