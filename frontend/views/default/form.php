<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use bl\cms\partner\frontend\Module as PartnerModule;

/**
 * View file for default controller in frontend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\frontend\models\forms\PartnerRequest $model
 * @var integer $legalId
 * @var integer $langId
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<div class="col-md-12">
    <h1 class="text-center">
        <?= Html::encode(PartnerModule::t('request', 'Request to be a partner')) ?>
    </h1>
    <div class="col-md-6 col-md-offset-3">
        <?php $form = ActiveForm::begin([
            'action' => ['send-request'],
            'enableAjaxValidation' => true,
        ]) ?>
            <?= $form->field($model, 'companyName') ?>
            <?= $form->field($model, 'officialCompanyName') ?>
            <?= $form->field($model, 'site') ?>
            <?= $form->field($model, 'city')
                    ->label(PartnerModule::t('request', 'City')) ?>
            <?php
            $legalLink = Html::a(
                PartnerModule::t('request', 'legal agreement'),
                Url::toRoute([
                    '/legal',
                    'legalId' => $legalId,
                    'langId' => $langId
                ]),
                ['target' => '_blank']
            );
            $label = PartnerModule::t('request', 'I agree with the condition of {legal-link}', [
                'legal-link' => $legalLink
            ]); ?>
            <?= $form->field($model, 'acceptLegalAgreement')
                    ->checkbox()
                    ->label($label) ?>
            <?= Html::submitButton(PartnerModule::t('request', 'Send'), [
                'class' => 'btn btn-success pull-right'
            ]) ?>
        <?php $form->end() ?>
    </div>
</div>
