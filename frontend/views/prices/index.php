<?php
use bl\cms\partner\frontend\widgets\PersonalAreaNav;
use bl\cms\partner\backend\Module as PartnerModule;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

/**
 * View file for materials controller in backend module
 *
 * @var \yii\web\View $this
 *
 * @author Vadim Gutsulyak
 */

$this->title = PartnerModule::t('partner', 'Prices page');
?>



<div class="row">
    <div class="col-md-3">
        <?= PersonalAreaNav::widget() ?>
    </div>
    <div class="col-md-9">
        <?php $form = ActiveForm::begin([
            "action" => "load",
            "method" => "post"
        ]) ?>
            <?= Html::submitButton(
                PartnerModule::t('prices', 'Load'),
                ['class' => 'btn btn-success']
            ) ?>
        <?php $form->end() ?>
    </div>
</div>