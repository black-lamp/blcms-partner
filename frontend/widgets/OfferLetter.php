<?php
namespace bl\cms\partner\frontend\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;

use bl\cms\partner\common\entities\CommercialOffer;
use bl\cms\partner\common\entities\CompanyEmployee;
use bl\cms\partner\common\entities\PartnerCompany;

use bl\cms\shop\common\entities\Product;

/**
 * Widget renders the offer letter
 *
 * @property integer $offerId
 * @property integer $logoRootUrl
 * @property float $discount
 * @property float $sum
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class OfferLetter extends Widget
{
    /**
     * @var integer
     */
    public $offerId;
    /**
     * @var string
     */
    public $logoRootUrl;
    /**
     * @var float
     */
    public $discount;
    /**
     * @var float
     */
    public $sum;

    /**
     * @var CommercialOffer
     */
    private $_offer;
    /**
     * @var PartnerCompany
     */
    private $_company;
    /**
     * @var Product[]
     */
    private $_products;


    /**
     * @inheritdoc
     */
    public function init()
    {
        /** @var CommercialOffer $offer */
        $this->_offer = CommercialOffer::find()
            ->where(['id' => $this->offerId])
            ->with('items')
            ->one();

        $companyId = CompanyEmployee::find()
            ->select('companyId')
            ->where(['id' => $this->_offer->employeeId])
            ->scalar();

        $this->_company = PartnerCompany::find()
            ->where(['id' => $companyId])
            ->with('info')
            ->one();

        $this->_products = Product::find()
            ->where(['id' => ArrayHelper::getColumn($this->_offer->items, 'productId')])
            ->with('category')
            ->orderBy('category_id')
            ->all();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('offer-letter', [
            'offer' => $this->_offer,
            'company' => $this->_company,
            'products' => $this->_products,
            'logoRootUrl' => $this->logoRootUrl,
            'discount' => $this->discount,
            'sum' => $this->sum
        ]);
    }
}
