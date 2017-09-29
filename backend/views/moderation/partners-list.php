<?php
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\partner\backend\Module as PartnerModule;

/**
 * View file for moderation controller in backend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\UserRequest[] $requests
 * @var string $message
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = Yii::t('moderation', 'Moderation of partners requests');
?>

<div class="ibox">
    <?php if (!empty($message)): ?>
        <div class="alert alert-success">
            <?= $message ?>
        </div>
    <?php endif; ?>
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
                    <?= PartnerModule::t('moderation', 'Company name') ?>
                </td>
                <td>
                    <?= PartnerModule::t('moderation', 'Official company name') ?>
                </td>
                <td>
                    <?= PartnerModule::t('moderation', 'City') ?>
                </td>
                <td>
                    <?= PartnerModule::t('moderation', 'Site') ?>
                </td>
                <td>
                    <?= PartnerModule::t('moderation', 'User') ?>
                </td>
                <td>
                    <?= PartnerModule::t('moderation', 'User phone') ?>
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
                        <?= Html::encode($request->companyName) ?>
                    </td>
                    <td>
                        <?= Html::encode($request->officialCompanyName) ?>
                    </td>
                    <td>
                        <?= Html::encode($request->city) ?>
                    </td>
                    <td>
                        <?= Html::encode($request->site) ?>
                    </td>
                    <td>
                        <?= Html::a(
                            $request->user->email,
                            Url::toRoute(['/user/admin/update', 'id' => $request->userId]),
                            ['target' => '_blank']
                        ) ?>
                    </td>
                    <td>
                        <?php if (!empty($request->user->profile->phone)): ?>
                            <?= Html::a(
                                $request->user->profile->phone,
                                'tel:' . $request->user->profile->phone
                            ) ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= Html::a(
                            PartnerModule::t('moderation', 'Accept'),
                            Url::toRoute(['accept', 'requestId' => $request->id]),
                            ['class' => 'btn btn-sm btn-primary']
                        ) ?>
                        <?= Html::a(
                            PartnerModule::t('moderation', 'Decline'),
                            Url::toRoute(['decline', 'requestId' => $request->id]),
                            ['class' => 'btn btn-sm btn-danger']
                        ) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
