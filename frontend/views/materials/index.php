<?php
use yii\helpers\Html;

use bl\cms\partner\frontend\widgets\Message;
use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\frontend\widgets\PersonalAreaNav;

/**
 * View file for materials controller in frontend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\MaterialCategory[] $materialCategories
 * @var \bl\cms\partner\common\components\FileManager $fileManager
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<div class="row">
    <div class="col-md-3">
        <?= PersonalAreaNav::widget() ?>
    </div>
    <div class="col-md-9">
        <?php if (Yii::$app->user->can('marketingManage')): ?>
            <?= $this->render('_materials', [
                'materialCategories' => $materialCategories,
                'fileManager' => $fileManager
            ]); ?>
        <?php else: ?>
            <?= Message::widget([
                'message' => PartnerModule::t(
                    'materials',
                    'This function allowed only for partners. Read more about {partners-link}',
                    [
                        'partners-link' => Html::a(PartnerModule::t('materials', 'partners'), '#')
                    ]
                ),
            ]) ?>
        <?php endif; ?>
    </div>
</div>
