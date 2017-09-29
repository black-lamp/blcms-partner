<?php
namespace bl\cms\partner\backend\controllers;

use bl\cms\partner\common\entities\OfferStatus;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

use bl\cms\partner\backend\Module as PartnerModule;
use bl\cms\partner\common\entities\CommercialOffer;
use bl\cms\partner\common\entities\CommercialOfferItem;
use bl\cms\partner\common\entities\CompanyEmployee;

use bl\cms\shop\common\entities\Product;

/**
 * Offer controller for Partner backend module
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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'view',
                    'set-status',
                    'offer-list',
                    'reservation-list',
                    'order-list',
                    'product-list'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'view',
                            'offer-list',
                            'reservation-list',
                            'order-list',
                            'product-list'
                        ],
                        'roles' => ['viewPartnerOffers']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'set-status',
                        ],
                        'roles' => ['setPartnerOfferStatus']
                    ]
                ]
            ],
        ];
    }


    /**
     * Renders list of offers
     *
     * @param $id integer
     *
     * @return string
     */
    public function actionView($id)
    {
        $offer = CommercialOffer::findOne($id);
        $adminStatuses = OfferStatus::findAll([
                OfferStatus::STATUS_RESERVATION_ACCEPTED,
                OfferStatus::STATUS_ORDER_ACCEPTED,
                OfferStatus::STATUS_ORDER_COMPLETED,
                OfferStatus::STATUS_CANCELED,
            ]);

        return $this->render('offer-view', [
            'offer' => $offer,
            'statuses' => $adminStatuses
        ]);
    }


    /**
     * Renders list of offers
     *
     * @param integer $offerId
     * @param integer $statusId
     * @param bool $sendEmail
     *
     * @return string
     */
    public function actionSetStatus($offerId, $statusId, $sendEmail = false)
    {
        $status = OfferStatus::findOne($statusId);

        if(!empty($status)) {
            $offer = CommercialOffer::findOne($offerId);

            if(!empty($offer)) {
                $offer->statusId = $status->id;
                $offer->save();
            }
        }

        return $this->redirect(['view', 'id' => $offerId]);
    }


    /**
     * Renders list of offers
     *
     * @return string
     */
    public function actionOfferList()
    {
        $offers = CommercialOffer::find()
            ->with('employee.user')
            ->with('status')
            ->orderBy(['statusId' => SORT_ASC, 'id' => SORT_DESC])
            ->all();

        $employees = CompanyEmployee::find()
            ->where(['id' => ArrayHelper::getColumn($offers, 'employee.id')])
            ->with('company')
            ->indexBy('id')
            ->all();

        return $this->render('offer-list', [
            'offers' => $offers,
            'employees' => $employees
        ]);
    }


    /**
     * Renders list with products reservation requests
     *
     * @return string
     */
    public function actionReservationList()
    {
        $offers = CommercialOffer::find()
            ->with('employee.user')
            ->onReservation()
            ->all();

        $employees = CompanyEmployee::find()
            ->where(['id' => ArrayHelper::getColumn($offers, 'employee.id')])
            ->with('company')
            ->indexBy('id')
            ->all();

        return $this->render('reservation-list', [
            'offers' => $offers,
            'employees' => $employees
        ]);
    }

    public function actionOrderList()
    {
        $offers = CommercialOffer::find()
            ->with('employee.user')
            ->onOrder()
            ->all();

        $employees = CompanyEmployee::find()
            ->where(['id' => ArrayHelper::getColumn($offers, 'employee.id')])
            ->with('company')
            ->indexBy('id')
            ->all();

        return $this->render('order-list', [
            'offers' => $offers,
            'employees' => $employees
        ]);
    }
    /**
     * Info about reservation or order of the offer
     *
     * @param integer $offerId
     * @return string
     */
    public function actionProductList($offerId)
    {
        $items = CommercialOfferItem::find()
            ->select('productId')
            ->where(['offerId' => $offerId])
            ->asArray()
            ->all();

        $products = Product::findAll(['id' => ArrayHelper::getColumn($items, 'productId')]);

        return $this->render('product-list', [
            'products' => $products
        ]);
    }

}
