<?php
use yii\helpers\Html;

use yii\helpers\Url;

use bl\cms\partner\backend\Module as PartnerModule;

use bl\files\icons\FileIconWidget;
use yii\widgets\ActiveForm;

/**
 * View file for materials controller in backend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\MaterialCategoryTranslation $model
 * @var \bl\cms\partner\common\entities\Material[] $materials
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('materials', 'Edit category');
$this->params['breadcrumbs'][] = [
    'label' => PartnerModule::t('materials', 'Materials category list'),
    'url' => ['/partner/materials']
];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ibox">
    <div class="ibox-content">
        <?= $this->render('_category-form', ['model' => $model]) ?>
        <?php if (!empty($materials)): ?>
            <div class="row">
                <div class="col-md-12">
                    <h2>
                        <?= PartnerModule::t('materials', 'Material files') ?>
                    </h2>
                    <?php foreach ($materials as $material): ?>
                        <div class="file-box">
                            <div class="file">
                                <span class="corner"></span>
                                <div class="icon">
                                    <?php $fileIcon = FileIconWidget::begin([
                                        'useDefaultIcons' => true
                                    ]) ?>
                                    <?= $fileIcon->getIcon($material->fileName) ?>
                                    <?php $fileIcon->end() ?>
                                </div>
                                <div class="file-name">
                                    <a href="#">
                                        <?= $material->fileName ?>
                                    </a>
                                    <br>
                                    <small>
                                        <?= PartnerModule::t('materials', 'Added: {date}', [
                                            'date' => Yii::$app->formatter->asDatetime($material->createdAt)
                                        ]) ?>
                                    </small>
                                    <?= Html::a(
                                        PartnerModule::t('materials', 'Edit title'),
                                        Url::toRoute(['edit-material', 'id' => $material->id])
                                    ) ?>
                                    <?= Html::a(
                                        PartnerModule::t('materials', 'Delete'),
                                        Url::toRoute(['delete-material', 'id' => $material->id]),
                                        ['class' => 'text-danger pull-right']
                                    ) ?>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
