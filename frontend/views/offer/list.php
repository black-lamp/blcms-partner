<?php
use yii\helpers\Html;
use yii\helpers\Url;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\frontend\widgets\PersonalAreaNav;
use bl\cms\partner\common\widgets\StatusLabel;
use bl\cms\partner\common\entities\OfferStatus;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\CommercialOffer[] $offers
 * @var \bl\cms\partner\common\entities\CommercialOffer[] $employeeOffers
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<div class="row">
    <div class="col-md-3">
        <?= PersonalAreaNav::widget() ?>
    </div>
    <div class="col-md-9">
        <?php if (!empty($offers)): ?>
            <h1 class="text-center">
                <?= PartnerModule::t('offer', 'List of commercial offers') ?>
            </h1>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <?= PartnerModule::t('offer', 'Title') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Status') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Actions') ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($offers as $number => $offer): ?>
                    <tr>
                        <td>
                            <?= $number + 1 ?>
                        </td>
                        <td>
                            <?= Html::encode($offer->title) ?>
                        </td>
                        <td>
                            <?php $widget = StatusLabel::begin([
                                'statuses' => [
                                    OfferStatus::STATUS_UNDEFINED => 'default',
                                    OfferStatus::STATUS_ON_RESERVATION => 'warning',
                                    OfferStatus::STATUS_ORDERING => 'warning',
                                    OfferStatus::STATUS_SENT_TO_CLIENT => 'success'
                                ]
                            ]) ?>
                                <?= $widget->getLabel(
                                    $offer->statusCode(),
                                    $offer->status->translation->title
                                ) ?>
                            <?php $widget->end() ?>
                        </td>
                        <td>
                            <?= Html::a(
                                PartnerModule::t('offer', 'Reservation of products'),
                                Url::toRoute(['reservation', 'offerId' => $offer->id]),
                                ['class' => 'btn btn-xs btn-warning']
                            ) ?>
                            <?= Html::a(
                                PartnerModule::t('offer', 'Ordering products'),
                                Url::toRoute(['ordering', 'offerId' => $offer->id]),
                                ['class' => 'btn btn-xs btn-primary']
                            ) ?>
                            <?= Html::a(
                                PartnerModule::t('offer', 'View'),
                                Url::toRoute(['view', 'id' => $offer->id]),
                                ['class' => 'btn btn-xs btn-success']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        <?php if (isset($employeeOffers)): ?>
            <h2 class="text-center">
                <?= PartnerModule::t('offers', 'Employees offers') ?>
            </h2>
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <?= PartnerModule::t('offer', 'Employee') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Title') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Status') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Actions') ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($employeeOffers as $number => $offer): ?>
                    <tr>
                        <td>
                            <?= $number + 1 ?>
                        </td>
                        <td>
                            <?= $offer->employee->user->email ?>
                        </td>
                        <td>
                            <?= Html::encode($offer->title) ?>
                        </td>
                        <td>
                            <?= $offer->status->translation->title ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
