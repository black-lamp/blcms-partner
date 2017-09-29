<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\frontend\widgets\PersonalAreaNav;

/**
 * View file for guarantee controller in frontend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\frontend\models\forms\GuaranteeRegistration $model
 * @var string $message
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('guarantee', 'Guarantee registration form');
?>
<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?= $message ?>
    </div>
<?php endif; ?>
<div class="row">
    <div class="col-md-3">
        <?= PersonalAreaNav::widget() ?>
    </div>
    <div class="col-md-9">
        <h1 class="text-center">
            <?= $this->title ?>
        </h1>
        <?php $form = ActiveForm::begin() ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'productSku') ?>
        <?= $form->field($model, 'companyName') ?>
        <?= $form->field($model, 'blankNumber') ?>
        <?= Html::submitButton(
            PartnerModule::t('guarantee', 'Send'),
            ['class' => 'btn btn-success pull-right']
        ) ?>
        <?php $form->end() ?>
    </div>
</div>
