<?php
namespace bl\cms\partner\common\entities;

use bl\cms\shop\common\components\user\models\UserGroup;
use bl\cms\shop\common\entities\Product;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

use bl\cms\partner\common\traits\OfferTrait;
use bl\cms\partner\common\interfaces\StatusInterface;
use bl\cms\partner\common\db\CommercialOfferQuery;

/**
 * This is the model class for table "partner_offer".
 *
 * @property integer $id
 * @property integer $employeeId
 * @property integer $statusId
 * @property string $title
 * @property string $subject
 * @property string $additional_information
 *
 * @property string $created_at
 * @property string $updated_at
 *
 * @property integer $itemsCount
 * @property integer $ownItemsCount
 * @property integer $employeeProductsSum
 *
 * @property CompanyEmployee $employee
 * @property CommercialOfferItem[] $items
 * @property PartnerOfferOwnItem[] $ownItems
 * @property OfferStatus $status
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CommercialOffer extends \yii\db\ActiveRecord implements StatusInterface
{
    use OfferTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%partner_offer}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['employeeId', 'title'], 'required'],
            [['employeeId', 'statusId'], 'integer'],
            [['statusId'], 'default', 'value' => 1],
            [['title'], 'string', 'max' => 500],
            [['subject'], 'string', 'max' => 500],
            [['additional_information'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('partner.entity', 'ID'),
            'employeeId' => Yii::t('partner.entity', 'Employee ID'),
            'statusId'   => Yii::t('partner.entity', 'Status ID'),
            'title'      => Yii::t('partner.entity', 'Title'),
            'subject'      => Yii::t('partner.entity', 'Subject'),
            'additional_information'      => Yii::t('partner.entity', 'Additional information'),
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     * @return CommercialOfferQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommercialOfferQuery(get_called_class());
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(CompanyEmployee::className(), ['id' => 'employeeId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItems()
    {
        return $this->hasMany(CommercialOfferItem::className(), ['offerId' => 'id']);
    }

    /**
     * @return int
     */
    public function getItemsCount() {
        return count($this->items);
    }

    /**
     * @param $userGroup
     * @return int
     */
    public function getProductsSum($userGroup = UserGroup::USER_GROUP_ALL_USERS) {
        /* @var Product $products */

        $sum = 0;

        $offerItems = $this->items;
        $products = Product::find()
            ->where(['id' => ArrayHelper::getColumn($this->items, 'productId')])
            ->with('category')
            ->indexBy('id')
            ->all();

        if (!empty($offerItems)) {
            foreach ($offerItems as $item) {
                $product = $products[$item->productId];
                if($product != null) {
                    if ($product->hasCombinations() && !empty($item->combinationId)) {
                        $priceEntity = $product->getCombination($item->combinationId)
                            ->getPriceByUserGroup($userGroup);
                        $sum += $priceEntity->getDiscountPrice() * $item->quantity;
                    }
                    elseif ($priceEntity = $product->getPriceByUserGroup($userGroup)) {
                        /** @var \bl\cms\shop\common\entities\Price $priceEntity */
                        $sum += $priceEntity->getDiscountPrice() * $item->quantity;
                    }

                    if ($item->hasAdditionalProducts()) {
                        foreach ($item->additionalProducts as $additionalProduct) {
                            $sum += $additionalProduct->product->getPriceByUserGroup($userGroup)->discountPrice * $additionalProduct->number;
                        }
                    }
                }
            }

        }

        return $sum;
    }

    /**
     * @return int
     */
    public function getEmployeeProductsSum() {
        return $this->getProductsSum($this->employee->user->user_group_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(OfferStatus::className(), ['id' => 'statusId']);
    }

    /**
     * Get employee offers
     *
     * @param integer $companyId
     * @return null|CommercialOffer[]
     */
    public static function getEmployeeOffers($companyId)
    {
        $directorId = PartnerCompany::find()
            ->select('ownerId')
            ->where(['id' => $companyId])
            ->scalar();

        $employees = CompanyEmployee::find()
            ->select('id')
            ->where(['companyId' => $companyId])
            ->andWhere(['<>', 'userId', $directorId])
            ->asArray()
            ->all();

        if (!is_null($employees)) {
            $offers = CommercialOffer::find()
                ->where(['employeeId' => ArrayHelper::getColumn($employees, 'id')])
                ->with(['status', 'employee.user'])
                ->all();

            return $offers;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function statusCode()
    {
        return $this->statusId;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnItems()
    {
        return $this->hasMany(PartnerOfferOwnItem::className(), ['offer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnItemsCount()
    {
        return count($this->ownItems);
    }
}
