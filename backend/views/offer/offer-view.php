<?php
use bl\cms\partner\backend\Module as PartnerModule;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\CommercialOffer $offer
 * @var \bl\cms\partner\common\entities\OfferStatus[] $statuses
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('offer.view', 'Offer page');

?>

<div class="ibox">
    <div class="ibox-title">
        <h5>
            <i class="fa fa-shopping-cart"></i>
            <?= PartnerModule::t('offer.view', 'Offer') . ' "' . $offer->title . '"' ?>
            <span class="label label-primary" style="background-color: <?= $offer->status->color ?>; float: none;">
                <?= $offer->status->translation->title ?>
            </span>
        </h5>

        <?php if (\Yii::$app->user->can('setPartnerOfferStatus')) : ?>
            <div class="ibox-tools">
                <?php foreach ($statuses as $status): ?>
                    <?= Html::a($status->translation->actionText, [
                        'set-status',
                        'offerId' => $offer->id,
                        'statusId' => $status->id,
                    ], [
                        'class' => 'btn btn-xs btn-primary',
                        'style' => 'background-color: ' . $status->color . '; border:none;'
                    ]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
    <div class="ibox-content">
        <?= Html::tag('iframe', '', [
            'src' => Yii::$app->urlManagerFrontend->createAbsoluteUrl([
                '/partner/offer/view',
                'id' => $offer->id,
                'partial' => true
            ], true),
            'class' => 'embed-responsive-item',
            'width' => '800',
            'height' => '1200',
        ]) ?>
    </div>
</div>
