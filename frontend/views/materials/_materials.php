<?php
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\partner\frontend\Module as PartnerModule;

use bl\files\icons\FileIconWidget;

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

<?php foreach ($materialCategories as $category): ?>
    <?php if (!empty($category->materials)): ?>
        <h3>
            <?= $category->translation->title ?>
        </h3>
        <div class="files">
            <?php foreach ($category->materials as $material): ?>
                <?php $materialId = "material-$material->id"; ?>
                <div class="media">
                    <div class="media-left">
                        <div class="media-object" data-toggle="modal" data-target="#<?= $materialId ?>">
                            <?php $fileIcon = FileIconWidget::begin([
                                'useDefaultIcons' => true
                            ]) ?>
                            <?= $fileIcon->getIcon($material->fileName) ?>
                            <?php $fileIcon->end() ?>
                        </div>
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?= Html::a(
                                $material->translation->title,
                                Url::toRoute(['download', 'id' => $material->id])
                            ) ?>
                        </h4>
                        <span class="label label-info">
                            <?= PartnerModule::t('materials', 'Added: {date}', [
                                'date' => Yii::$app->formatter->asDatetime($material->createdAt)
                            ]) ?>
                        </span>
                    </div>
                </div>
                <hr>
                <div class="modal fade" id="<?= $materialId ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <?php
                                $pathToFile = $fileManager->getPathToFile($material->fileName);
                                $url = "http://docs.google.com/gview?url=<?=$pathToFile ?>&embedded=true";
                                ?>
                                <iframe src="<?= $url ?>" width="100%" height="100%"></iframe>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">
                                    <?= PartnerModule::t('materials', 'Close') ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
