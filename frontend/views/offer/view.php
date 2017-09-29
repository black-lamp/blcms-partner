<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\frontend\Module as PartnerModule;

/**
 * @var \yii\web\View $this
 * @var integer $offerId
 * @var float $discount
 * @var float $wholesaleSum
 * @var float $sum
 * @var integer $walrus
 * @var string $preview
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<div class="row">
    <div class="col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?= PartnerModule::t('offer', 'Offer letter') ?>
            </div>
            <div class="embed-responsive embed-responsive-4by3">
                <?= Html::tag('iframe', '', [
                    'src' => Url::to(['view', 'id' => $offerId, 'discount' => $discount, 'partial' => true]),
                    'class' => 'embed-responsive-item',
                ]) ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <dl>
            <dt>
                <?= PartnerModule::t('offer', 'Discount:') ?>
            </dt>
            <dd>
                <?php $form = ActiveForm::begin(['method' => 'get']) ?>
                <?= Html::input('number', 'discount', Html::encode($discount), [
                        'min' => 0,
                        'max' => 100
                ]) ?>
                <?php $form->end() ?>
            </dd>
            <dt>
                <?= PartnerModule::t('offer', 'Wholesale sum:') ?>
            </dt>
            <dd>
                <?= $wholesaleSum ?>
            </dd>
            <dt>
                <?= PartnerModule::t('offer', 'Sum:') ?>
            </dt>
            <dd>
                <?= $sum ?>
            </dd>
            <dt>
                <?= PartnerModule::t('offer', 'Win:') ?>
            </dt>
            <dd>
                <?= $walrus . '%' ?>
            </dd>
        </dl>
    </div>
    <div class="col-md-6">
        <?= Html::a(
            PartnerModule::t('offer', 'Reservation of products'),
            Url::toRoute(['reservation', 'offerId' => $offerId]),
            ['class' => 'btn btn-warning']
        ) ?>
        <?= Html::a(
            PartnerModule::t('offer', 'Ordering products'),
            Url::toRoute(['ordering', 'offerId' => $offerId]),
            ['class' => 'btn btn-primary']
        ) ?>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#sendOffer">
            <?= PartnerModule::t('offer', 'Send to client') ?>
        </button>
        <div class="modal fade" id="sendOffer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <?php $form = ActiveForm::begin() ?>
                        <div class="modal-body">
                            <?= Html::input('email', 'clientEmail', '', [
                                    'class' => 'form-control',
                                    'placeholder' => PartnerModule::t('offer', 'E-mail')
                            ]) ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <?= PartnerModule::t('offer', 'Close') ?>
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <?= PartnerModule::t('offer', 'Send') ?>
                            </button>
                        </div>
                    <?php $form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>
