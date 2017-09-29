<?php
use kartik\file\FileInput;
use yii\helpers\Url;
use yii\helpers\Html;
use bl\cms\partner\backend\Module as PartnerModule;

/**
 * View file for materials controller in backend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\MaterialCategory[] $categories
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('materials', 'Materials for marketing');
?>

<div class="ibox">
    <div class="ibox-content">
        <?= Html::a(
            PartnerModule::t('materials', 'Create category'),
            Url::toRoute(['create']),
            ['class' => 'btn btn-sm btn-primary pull-right']
        ) ?>
        <div class="clearfix"></div>
        <?php if (!empty($categories)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <?= PartnerModule::t('materials', 'Category name') ?>
                        </th>
                        <th>
                            <?= PartnerModule::t('materials', 'Actions') ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td>
                                <?= $category->translation->title ?>
                            </td>
                            <td>
                                <?php $modalId = "category-$category->id" ?>
                                <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#<?= $modalId ?>">
                                    <?= PartnerModule::t('materials', 'Add material') ?>
                                </button>
                                <div class="modal modal-large fade" id="<?= $modalId ?>" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <?= FileInput::widget([
                                                    'name' => 'materialFiles',
                                                    'options' => ['multiple' => true],
                                                    'pluginOptions' => [
                                                        'uploadUrl' => Url::toRoute(['add-material', 'categoryId' => $category->id]),
                                                        'maxFileCount' => 10
                                                    ]
                                                ]) ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                                    <?= PartnerModule::t('materials', 'Close') ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?= Html::a(
                                    PartnerModule::t('materials', 'Edit'),
                                    Url::toRoute(['edit', 'id' => $category->id]),
                                    ['class' => 'btn btn-xs btn-warning']
                                ) ?>
                                <?= Html::a(
                                    PartnerModule::t('materials', 'Delete'),
                                    Url::toRoute(['delete', 'id' => $category->id]),
                                    ['class' => 'btn btn-xs btn-danger']
                                ) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
