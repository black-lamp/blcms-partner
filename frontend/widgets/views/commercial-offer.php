<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\frontend\Module as PartnerModule;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\CommercialOffer[] $offers
 * @var \bl\cms\partner\common\entities\CommercialOffer $model
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<div class="col-md-6">
    <?php $title = PartnerModule::t('offer', 'Create new offer') ?>
    <?php if (!empty($offers)): ?>
        <?php $title = PartnerModule::t('offer', 'or create new') ?>
        <h3>
            <?= PartnerModule::t('offer', 'Select offer') ?>
        </h3>
        <?= Html::dropDownList(
            'offerId',
            'id',
            ArrayHelper::map($offers, 'id', 'title'),
            [
                'class' => 'form-control',
                'prompt' => PartnerModule::t('offer', '- Select offer -')
            ]
        ) ?>
    <?php endif; ?>
    <h3>
        <?= $title ?>
    </h3>
    <?= Html::textInput('offerTitle', '', ['class' => 'form-control']) ?>
</div>
