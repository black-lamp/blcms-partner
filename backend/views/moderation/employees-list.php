<?php
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\partner\backend\Module as PartnerModule;

/**
 * View file for moderation controller in backend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\EmployeeRequest[] $requests
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = Yii::t('moderation', 'Moderation of employee requests');
?>

<div class="ibox">
    <div class="ibox-title">
        <h1 class="title">
            <?= $this->title ?>
        </h1>
    </div>
    <div class="ibox-content">
        <table class="table">
            <thead>
                <tr>
                    <td>
                        <?= PartnerModule::t('moderation', 'Company ID') ?>
                    </td>
                    <td>
                        <?= PartnerModule::t('moderation', 'User') ?>
                    </td>
                    <td>
                        <?= PartnerModule::t('moderation', 'Request date') ?>
                    </td>
                    <td>
                        <?= PartnerModule::t('moderation', 'Actions') ?>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($requests as $request): ?>
                    <tr>
                        <td>
                            <?= Html::a(
                                $request->company->name,
                                Url::toRoute(['company/view', 'id' => $request->companyId]),
                                ['target' => '_blank']
                            ) ?>
                        </td>
                        <td>
                            <?= Html::a(
                                $request->user->email,
                                Url::toRoute(['/user/admin/update', 'id' => $request->userId]),
                                ['target' => '_blank']
                            ) ?>
                        </td>
                        <td>
                            <?= Yii::$app->formatter->asDatetime($request->createdAt) ?>
                        </td>
                        <td>
                            <?= Html::a(
                                    PartnerModule::t('moderation', 'Accept'),
                                    Url::toRoute(['accept-employee', 'requestId' => $request->id]),
                                    ['class' => 'btn btn-sm btn-primary']
                            ) ?>
                            <?= Html::a(
                                    PartnerModule::t('moderation', 'Decline'),
                                    Url::toRoute(['decline-employee', 'requestId' => $request->id]),
                                    ['class' => 'btn btn-sm btn-danger']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
