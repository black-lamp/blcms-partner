<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\backend\Module as PartnerModule;

use bl\multilang\widgets\Languages;

/**
 * View file for materials controller in backend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\MaterialCategoryTranslation $model
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('materials', 'Create category');
$this->params['breadcrumbs'][] = [
    'label' => PartnerModule::t('materials', 'Materials category list'),
    'url' => ['/partner/materials']
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ibox">
    <div class="ibox-content">
        <?php $form = ActiveForm::begin() ?>
            <?= $form->field($model, 'languageId')
                ->widget(Languages::class) ?>
            <?= $form->field($model, 'title') ?>
            <?= Html::submitButton(
                PartnerModule::t('materials', 'Save'),
                ['class' => 'btn btn-primary pull-right']
            ) ?>
        <div class="clearfix"></div>
        <?php $form->end() ?>
    </div>
</div>
