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
?>

<?php $form = ActiveForm::begin() ?>
    <?= $form->field($model, 'title') ?>
    <?= Html::submitButton(
        PartnerModule::t('materials', 'Save'),
        ['class' => 'btn btn-primary pull-right']
    ) ?>
    <div class="clearfix"></div>
<?php $form->end() ?>
