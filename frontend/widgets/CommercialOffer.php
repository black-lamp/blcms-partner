<?php
namespace bl\cms\partner\frontend\widgets;

use bl\cms\partner\common\entities\CompanyEmployee;
use Yii;
use yii\base\Widget;

use bl\cms\partner\common\entities\CommercialOffer as CommercialOfferEntity;

/**
 * Widget for adding product to commercial offer
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CommercialOffer extends Widget
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->can('createCommercialOffer')) {
            $employeeId = CompanyEmployee::find()
                ->select('id')
                ->where(['userId' => Yii::$app->user->id])
                ->scalar();

            $offers = CommercialOfferEntity::find()
                ->select(['id', 'title'])
                ->where(['employeeId' => $employeeId])
                ->asArray()
                ->all();

            $model = new CommercialOfferEntity();

            return $this->render('commercial-offer', [
                'offers' => $offers,
                'model' => $model
            ]);
        }
    }
}
