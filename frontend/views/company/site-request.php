<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\frontend\Module as PartnerModule;

/**
 * @var \bl\cms\partner\frontend\models\forms\SubsiteRequestForm $pgModel
 * @var \bl\cms\partner\frontend\models\forms\SubsiteRequestForm $personalModel
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('site', 'Request for site creation');
?>
<div>
    <h1>
        <?= $this->title ?>
    </h1>
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#pg" aria-controls="home" role="tab" data-toggle="tab">
                <?= PartnerModule::t('site', 'In pg-pool.com sub-domain') ?>
            </a>
        </li>
        <li role="presentation">
            <a href="#personal" aria-controls="profile" role="tab" data-toggle="tab">
                <?= PartnerModule::t('site', 'In my domain') ?>
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="pg">
            <?php $form = ActiveForm::begin([
                'action' => ['send-subsite-request'],
                'id' => 'sdfsdf',
                'enableAjaxValidation' => true
            ]) ?>
            <?= $form->field($pgModel,'domainName')
                ->label(PartnerModule::t('site', 'Site name')) ?>
            <?= Html::submitButton(
                PartnerModule::t('site', 'Send request'),
                ['class' => 'btn btn-success pull-right']
            ) ?>
            <?php $form->end() ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="personal">
            <?php $form = ActiveForm::begin([
                'action' => ['send-site-request'],
            ]) ?>
            <?= $form->field($personalModel,'domainName')
                ->label(PartnerModule::t('site', 'Domain name')) ?>
            <?= $form->field($personalModel, 'hasHosting')
                ->checkbox() ?>
            <?= Html::submitButton(
                PartnerModule::t('site', 'Send request'),
                ['class' => 'btn btn-success pull-right']
            ) ?>
            <?php $form->end() ?>
        </div>
    </div>
</div>
