<?php
namespace bl\cms\partner\frontend\controllers;

use bl\cms\cart\CartComponent;
use bl\cms\shop\backend\components\form\ProductImageForm;
use bl\cms\shop\common\entities\ProductAdditionalProduct;
use bl\cms\shop\frontend\widgets\models\AdditionalProductForm;
use bl\cms\partner\common\entities\CommercialOfferItemAdditionalProduct;
use bl\cms\partner\common\entities\PartnerOfferOwnItem;
use bl\cms\partner\frontend\components\PriceCalculator;
use bl\cms\partner\frontend\models\forms\CommercialOfferMail;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\common\entities\CommercialOffer;
use bl\cms\partner\common\entities\CommercialOfferItem;
use bl\cms\partner\common\entities\CompanyEmployee;
use bl\cms\partner\common\entities\PartnerCompany;

use bl\cms\cart\models\CartForm;
use bl\cms\shop\common\entities\Product;
use bl\cms\shop\common\components\user\models\UserGroup;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * Offer controller for Partner frontend module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class OfferController extends Controller
{
    /**
     * @var PartnerModule
     */
    public $module;
    /**
     * @inheritdoc
     */
    public $defaultAction = 'list';


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'add',
                    'list',
                    'view',
                    'reservation',
                    'ordering',
                    'remove-offer-item'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'add',
                            'list',
                            'reservation',
                            'ordering',
                            'remove-offer-item'
                        ],
                        'roles' => ['employee']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'view',
                        ],
                        'roles' => ['employee', 'viewPartnerOffers']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post']
                ]
            ]
        ];
    }

    /**
     * Create new offer or get existing offer
     *
     * @param integer $offerId
     * @param string $offerTitle
     * @return null|CommercialOffer
     */
    private function createOffer($offerId, $offerTitle)
    {
        $offer = null;
        if (empty($offerId)) {
            $employeeId = CompanyEmployee::find()
                ->select('id')
                ->where(['userId' => Yii::$app->getUser()->id])
                ->scalar();

            $offer = new CommercialOffer([
                'employeeId' => $employeeId,
                'title' => $offerTitle
            ]);
            $offer->insert();
        }
        else {
            $offer = CommercialOffer::findOne($offerId);
        }

        return $offer;
    }

    /**
     * Adds product to commercial offer
     * @return \yii\web\Response
     */
    public function actionAdd()
    {
        if (\Yii::$app->request->isPost) {
            $post = Yii::$app->getRequest()->post();

            /** @var CartComponent $cart */
            $cart = Yii::$app->get('cart');

            $cartModel = new CartForm();
            if ($cartModel->load($post) && $cartModel->validate()) {

                $offerId = (isset($post['offerId'])) ? $post['offerId'] : null;
                $offer = $this->createOffer($offerId, $post['offerTitle']);

                $combination = $cart->getCombination($cartModel->attribute_value_id, $cartModel->productId);
                $combinationId = ($combination) ? $combination->id : null;

                $cart->saveSelectedCombinationToSession($combination);

                $item = null;
                if ($item = CommercialOfferItem::findOne(['productId' => $cartModel->productId, 'combinationId' => $combinationId, 'offerId' => $offer->id])) {
                    $item->updateCounters(['quantity' => $cartModel->count]);
                }
                else {
                    $item = new CommercialOfferItem([
                        'offerId' => $offer->id,
                        'productId' => $cartModel->productId,
                        'combinationId' => $combinationId,
                        'quantity' => $cartModel->count
                    ]);
                    $item->insert();
                }

                $message = PartnerModule::t('offer', 'Error');
                if (!$item->hasErrors()) {

                    //Gets and saves additional products
                    $additionalProductForm = [];
                    $productAdditionalProducts = ProductAdditionalProduct::find()->where(['product_id' => $cartModel->productId])
                        ->indexBy('additional_product_id')->all();
                    foreach ($productAdditionalProducts as $key => $productAdditionalProduct) {
                        $additionalProductForm[$key] = new AdditionalProductForm();
                    }
                    if (!empty($productAdditionalProducts)) {
                        Model::loadMultiple($additionalProductForm, $post);
                        Model::validateMultiple($additionalProductForm);
                    }
                    foreach ($additionalProductForm as $additionalProduct) {
                        if (!empty($additionalProduct->productId)) {
                            $commercialOfferItemAdditionalProduct = CommercialOfferItemAdditionalProduct::find()
                                ->where(['partner_offer_item_id' => $item->id, 'additional_product_id' => $additionalProduct['productId']])
                                ->one();
                            if (empty($commercialOfferItemAdditionalProduct)) {
                                $commercialOfferItemAdditionalProduct = new CommercialOfferItemAdditionalProduct();
                                $commercialOfferItemAdditionalProduct->partner_offer_item_id = $item->id;
                                $commercialOfferItemAdditionalProduct->additional_product_id = $additionalProduct['productId'];
                                $commercialOfferItemAdditionalProduct->number = (!empty($additionalProduct['number'])) ?
                                    $additionalProduct['number'] : 1;
                                if ($commercialOfferItemAdditionalProduct->validate()) $commercialOfferItemAdditionalProduct->save();
                            }
                            else {
                                $commercialOfferItemAdditionalProduct->updateCounters(['number' => $additionalProduct['number']]);
                            }
                        }
                    }

                    $message = PartnerModule::t(
                        'offer',
                        'A product was successfully added to the {offer-link} offer', [
                        'offer-link' => Html::a($offer->title, Url::toRoute([
                            '/partner/offer/view',
                            'id' => $offer->id
                        ]))
                    ]);
                }

                Yii::$app->session->setFlash('partner-offer', $message);
                return $this->redirect(Yii::$app->request->referrer);
            }
        }

        return $this->redirect(Url::to(['/']));

    }

    /**
     * Renders list of commercial offers for user
     *
     * @return string
     */
    public function actionList()
    {
        $employee = $this->module->getCompanyManager()->currentEmployee();

        $offers = CommercialOffer::find()
            ->where(['employeeId' => $employee->id])
            ->with('status')
            ->all();

        $viewParams = [
            'offers' => $offers
        ];

        if (Yii::$app->user->can('seeEmployeesCommercialOffers')) {
            $companyId = PartnerCompany::find()
                ->select('id')
                ->where(['ownerId' => Yii::$app->user->id])
                ->scalar();

            $viewParams['employeeOffers'] = CommercialOffer::getEmployeeOffers($companyId);
        }

        return $this->render('list', $viewParams);
    }

    /**
     * View commercial offer
     *
     * @param integer $id Commercial offer ID
     * @param float $discount
     * @param bool $partial
     * @param bool $showPartnerPrices
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($id, $discount = 0.00, $partial = false, $showPartnerPrices = false)
    {
        /** @var CommercialOffer $offer */
        $offer = CommercialOffer::find()
            ->where(['id' => $id])
            ->with('items')
            ->one();

        if (empty($offer)) throw new NotFoundHttpException();

        $employee = CompanyEmployee::find()
            ->where(['id' => $offer->employeeId])
            ->with('user.profile')
            ->one();

        $companyId = CompanyEmployee::find()
            ->select('companyId')
            ->where(['id' => $offer->employeeId])
            ->scalar();

        $company = PartnerCompany::find()
            ->where(['id' => $companyId])
            ->with('info')
            ->one();

        /** @var Product[] $products */
        $products = Product::find()
            ->where(['id' => ArrayHelper::getColumn($offer->items, 'productId')])
            ->with('category')
            ->indexBy('id')
            ->all();

        /** @var PriceCalculator $calc */
        $calc = $this->module->getCalculator();
        $wholesaleSum = $calc->getProductsSumByUserGroup(
            $offer,
            $products,
            Yii::$app->user->identity->user_group_id
        );
        $sum = $calc->getProductsSumByUserGroup(
            $offer,
            $products,
            UserGroup::USER_GROUP_ALL_USERS
        );

        $walrus = $calc->calcWalrus($sum * (100 - $discount) / 100, $wholesaleSum);
        $walrusInPercent = $calc->calcWalrusInPercent($sum * (100 - $discount) / 100, $wholesaleSum);

        $preview = $this->renderPartial('_offer-letter', [
            'offer' => $offer,
            'employee' => $employee,
            'company' => $company,
            'products' => $products,
            'logoRootUrl' => $this->module->imagesRootUrl,
            'discount' => $discount,
            'sum' => $sum,
            'wholesaleSum' => $wholesaleSum,
            'showPartnerPrices' => $showPartnerPrices,
            'walrus' => $walrus,
            'walrusInPercent' => $walrusInPercent,
        ]);

        /** @var \yii\web\Request $request */
        $request = Yii::$app->get('request');
        if ($request->isPost) {

            Yii::$app->mailer->compose()
                ->setFrom(Yii::$app->params['webmasterEmail'])
                ->setTo($request->post('clientEmail'))
                ->setSubject($offer->subject ?? $offer->title)
                ->setHtmlBody($preview)
                ->send();

            Yii::$app->getSession()->setFlash(
                'success',
                \Yii::t('partner', 'Email was sent successfully')
            );

            return $this->redirect($request->referrer);
        }

        if ($partial == true) {
            return $preview;
        }

        $this->enableCsrfValidation = false;
        return $this->render('view', [
            'offerId' => $id,
            'offer' => $offer,
            'preview' => $preview,
            'discount' => $discount,
            'wholesaleSum' => $wholesaleSum,
            'sum' => $sum,
            'walrus' => $walrus,
            'walrusInPercent' => $walrusInPercent,
            'showPartnerPrices' => $showPartnerPrices,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionChangeLetter()
    {
        $request = Yii::$app->get('request');
        if ($request->isPost) {

            $post = $request->post();
            $commercialOfferModel = new CommercialOfferMail();

            if ($commercialOfferModel->load($post) && $commercialOfferModel->validate()) {
                $commercialOffer = CommercialOffer::findOne($commercialOfferModel->offerId);

                $commercialOffer->subject = $commercialOfferModel->subject;
                $commercialOffer->additional_information = $commercialOfferModel->additionalInformation;

                if ($commercialOffer->validate()) $commercialOffer->save();
            }
        }

        return $this->redirect($request->referrer);
    }

    /**
     * Sent request for reservation
     *
     * @param integer $offerId
     * @return \yii\web\Response
     */
    public function actionReservation($offerId)
    {
        $offer = CommercialOffer::findOne($offerId);
        $offer->setStatusOnReservation();

        Yii::$app->getSession()->setFlash(
            'success',
            \Yii::t('partner', 'Reservation request was sent successfully')
        );

        // TODO: Set flash message
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Sent request for ordering
     *
     * @param integer $offerId
     * @return \yii\web\Response
     */
    public function actionOrdering($offerId)
    {
        $offer = CommercialOffer::findOne($offerId);
        $offer->setStatusOrdering();

        Yii::$app->getSession()->setFlash(
            'success',
            \Yii::t('partner', 'Your order was successfully sent to website administrator')
        );

        // TODO: Set flash message
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * @param int $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionRemoveOfferItem(int $id)
    {
        if (\Yii::$app->user->can('editCommercialEmployee')) {

            $offerItem = CommercialOfferItem::findOne($id);
            if ($offerItem->offer->employee->userId == \Yii::$app->user->id) {
                CommercialOfferItem::deleteAll(['id' => $id]);
                return $this->redirect(\Yii::$app->request->referrer);
            }
        }
        else throw new ForbiddenHttpException();
    }

    /**
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionAddOwnItem()
    {
        if (\Yii::$app->request->isPost) {
            $post = \Yii::$app->request->post();
            $ownOfferItem = new PartnerOfferOwnItem();

            if ($ownOfferItem->load($post) && $ownOfferItem->validate()) {

                $offer = CommercialOffer::findOne($ownOfferItem->offer_id);
                if ($offer->employee->userId != \Yii::$app->user->id) throw new ForbiddenHttpException();


                //Saving image
                $image_form = new ProductImageForm();
                if ($image_form->load($post)) {
                    $image_form->image = UploadedFile::getInstance($image_form, 'image');

                    if (!empty($image_form->image)) {
                        if ($uploadedImageName = $image_form->upload()) {

                            $ownOfferItem->image = $uploadedImageName;
                        }
                    }
                }

                $ownOfferItem->save();

                return $this->redirect(\Yii::$app->request->referrer);
            }
            else throw new Exception('Validation error');
        }
        throw new NotFoundHttpException();
    }

    /**
     * @param int $itemId
     * @return \yii\web\Response
     */
    public function actionRemoveOwnItem(int $itemId)
    {
        $item = PartnerOfferOwnItem::findOne($itemId);
        if ($item->offer->employee->userId == \Yii::$app->user->id) {

            \Yii::$app->get('shop_imagable')->delete('shop-product', $item->image);

            $item->delete();
        }
        return $this->redirect(\Yii::$app->request->referrer);

    }
}
