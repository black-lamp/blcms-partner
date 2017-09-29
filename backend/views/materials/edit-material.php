<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\backend\Module as PartnerModule;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\MaterialTranslation $model
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<div class="ibox">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin() ?>
            <?= $form->field($model, 'title') ?>
            <?= Html::submitButton(
                PartnerModule::t('materials', 'Save'),
                ['class' => 'btn btn-primary pull-right']
            ) ?>
            <div class="clearfix"></div>
        <?php $form->end() ?>
    </div>
</div>
