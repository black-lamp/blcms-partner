<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\frontend\widgets\PersonalAreaNav;

use unclead\multipleinput\MultipleInput;

/**
 * View file for company controller in Partner frontend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\frontend\models\forms\EditCompanyInfo $model
 * @var string $imagesRootUrl
 * @var string $message
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = Yii::t('company', 'Edit company info');
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
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
        ]) ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'officialName') ?>
        <?= $form->field($model, 'site') ?>
        <?= $form->field($model->uploadedImage, 'file')
            ->fileInput() ?>
        <?= $form->field($model, 'emails')
            ->widget(MultipleInput::class, [
                'min'               => 1,
                'allowEmptyList'    => true,
                'enableGuessTitle'  => true,
                'columns' => [
                    [
                        'name' => 'id',
                        'type' => 'hiddenInput',
                        'value' => function ($data) {
                            return $data['id'];
                        }
                    ],
                    [
                        'name' => 'content',
                        'type' => 'textInput',
                        'title' => 'Email',
                        'value' => function ($data) {
                            return $data['content'];
                        }
                    ]
                ]
            ])
            ->label(false) ?>
        <?= $form->field($model, 'phones')
            ->widget(MultipleInput::class, [
                'min'               => 1,
                'allowEmptyList'    => true,
                'enableGuessTitle'  => true,
                'columns' => [
                    [
                        'name' => 'id',
                        'type' => 'hiddenInput',
                        'value' => function ($data) {
                            return $data['id'];
                        }
                    ],
                    [
                        'name' => 'content',
                        'type' => 'textInput',
                        'title' => 'Phone',
                        'value' => function ($data) {
                            return $data['content'];
                        }
                    ]
                ]
            ])
            ->label(false) ?>
        <?= Html::submitButton(
            PartnerModule::t('button', 'Save'),
            ['class' => 'btn btn-success pull-right']
        ) ?>
        <?php $form->end() ?>
    </div>
</div>
