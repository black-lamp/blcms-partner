<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use bl\cms\partner\backend\Module as PartnerModule;
use bl\cms\partner\common\entities\CompanyInfoCategory;
use bl\cms\partner\common\widgets\StatusLabel;
use bl\cms\partner\common\entities\CompanyStatus;
use bl\cms\partner\common\widgets\UserSearcher;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\PartnerCompany $company
 * @var boolean $isBlocked
 * @var \bl\cms\cart\common\components\user\models\Profile[] $users
 * @var \bl\cms\partner\common\entities\CompanyEmployee[] $employees
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<div class="ibox">
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">

                <?php if (\Yii::$app->user->can('blockPartnerCompany')) : ?>
                    <div class="m-b-md">
                        <?php if ($isBlocked): ?>
                            <?= Html::a(
                                PartnerModule::t('company.info', 'Un ban company'),
                                Url::toRoute(['un-ban', 'id' => $company->id]),
                                ['class' => 'btn btn-xs btn-primary pull-right']
                            ) ?>
                        <?php else: ?>
                            <?= Html::a(
                                PartnerModule::t('company.info', 'Ban company'),
                                Url::toRoute(['ban', 'id' => $company->id]),
                                ['class' => 'btn btn-xs btn-danger pull-right']
                            ) ?>
                        <?php endif; ?>
                        <h2>
                            <?= PartnerModule::t('company.info', 'Information about company') ?>
                        </h2>
                    </div>
                <?php endif; ?>

                <dl class="dl-horizontal">
                    <dt>
                        <?= PartnerModule::t('company.info', 'Status:') ?>
                    </dt>
                    <dd>
                        <?php $widget = StatusLabel::begin([
                            'statuses' => [
                                CompanyStatus::STATUS_ACTIVE => 'primary',
                                CompanyStatus::STATUS_BLOCKED => 'danger'
                            ]
                        ]) ?>
                        <?= $widget->getLabel(
                            $company->statusId,
                            $company->status->translation->title
                        ) ?>
                        <?php $widget->end() ?>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <dl class="dl-horizontal">
                    <dt>
                        <?= PartnerModule::t('company.info', 'Company name:') ?>
                    </dt>
                    <dd>
                        <?= Html::encode($company->name) ?>
                    </dd>
                    <dt>
                        <?= PartnerModule::t('company.info', 'Official company name:') ?>
                    </dt>
                    <dd>
                        <?= Html::encode($company->officialName) ?>
                    </dd>
                    <dt>
                        <?= PartnerModule::t('company.info', 'Site:') ?>
                    </dt>
                    <dd>
                        <?= Html::a(
                            Html::encode($company->siteLink),
                            $company->siteLink,
                            [
                                'class' => 'text-navy',
                                'target' => '_blank'
                            ]
                        ) ?>
                    </dd>
                </dl>
            </div>
            <div class="col-lg-7">
                <dl class="dl-horizontal">
                    <dt>
                        <?= PartnerModule::t('company.info', 'Owner:') ?>
                    </dt>
                    <dd>
                        <?= Html::a(
                            Html::encode($company->owner->email),
                            Url::toRoute(['/user/admin/update', 'id' => $company->owner->id]),
                            [
                                'class' => 'text-navy',
                                'target' => '_blank'
                            ]
                        ) ?>
                    </dd>
                    <dt>
                        <?= PartnerModule::t('company.info', 'Last updated:') ?>
                    </dt>
                    <dd>
                        <?= Yii::$app->getFormatter()->asDatetime($company->updatedAt) ?>
                    </dd>
                    <dt>
                        <?= PartnerModule::t('company.info', 'Created:') ?>
                    </dt>
                    <dd>
                        <?= Yii::$app->getFormatter()->asDatetime($company->createdAt) ?>
                    </dd>
                </dl>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-5">
                <dl class="dl-horizontal">
                    <dt>
                        <?= PartnerModule::t('company.info', 'Logo:') ?>
                    </dt>
                    <dd>
                        <?= Html::img(
                            Yii::$app->get('urlManagerFrontend')
                                ->createUrl('/images/partner/logos/' . $company->logo),
                            [
                                'width' => 200
                            ]
                        ) ?>
                    </dd>
                </dl>
            </div>
            <div class="col-md-7">
                <dl class="dl-horizontal">
                    <dt>
                        <?= PartnerModule::t('company.info', 'Emails:') ?>
                    </dt>
                    <dd>
                        <?php foreach ($company->info as $item): ?>
                            <?php if ($item->categoryId == CompanyInfoCategory::CATEGORY_EMAILS): ?>
                                <div>
                                    <?= Html::mailto(
                                        Html::encode($item->content),
                                        $item->content,
                                        ['class' => 'text-navy']
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </dd>
                    <dt>
                        <?= PartnerModule::t('company.info', 'Phones:') ?>
                    </dt>
                    <dd>
                        <?php foreach ($company->info as $item): ?>
                            <?php if ($item->categoryId == CompanyInfoCategory::CATEGORY_PHONES): ?>
                                <div>
                                    <?= Html::a(
                                        Html::encode($item->content),
                                        'tel:' . $item->content,
                                        ['class' => 'text-navy']
                                    ) ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </dd>
                </dl>
            </div>
        </div>

        <?php if (\Yii::$app->user->can('editPartnerCompany')) : ?>
            <!--Employees tabs-->
            <div class="row m-t-sm">
                <div class="col-lg-12">
                    <div class="panel blank-panel">
                        <div class="panel-heading">
                            <div class="panel-options">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab-1" data-toggle="tab">
                                            <?= PartnerModule::t('company.info', 'Employees') ?>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab-2" data-toggle="tab">
                                            <?= PartnerModule::t('company.info', 'Hire employee') ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab-1">
                                    <h3>
                                        <?= PartnerModule::t('company.info', 'Total count: {count}', [
                                            'count' => count($employees)
                                        ]) ?>
                                    </h3>
                                    <h3>
                                        <?= PartnerModule::t('company.info', 'List:') ?>
                                    </h3>
                                    <?php foreach ($employees as $emp): ?>
                                        <p>
                                            <?= Html::a(
                                                Html::encode($emp->user->username),
                                                Url::toRoute(['/user/admin/update', 'id' => $emp->userId]),
                                                [
                                                    'class' => 'text-navy',
                                                    'target' => '_blank'
                                                ]
                                            ) ?>
                                            -
                                            <?= Html::a(
                                                PartnerModule::t('company.info', 'Fire'),
                                                Url::toRoute(['fire-employee', 'id' => $emp->id]),
                                                ['class' => 'text-danger']
                                            ) ?>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                                <div class="tab-pane" id="tab-2">
                                    <?php $form = ActiveForm::begin([
                                        'action' => ['hire-employee']
                                    ]) ?>
                                    <?= Html::hiddenInput('companyId', $company->id) ?>
                                    <div class="form-group">
                                        <?= UserSearcher::widget([
                                            'inputName' => 'employeeId',
                                            'users' => $users
                                        ]) ?>
                                    </div>
                                    <div class="form-group">
                                        <?= Html::submitButton(
                                            PartnerModule::t('company.info', 'Hire'),
                                            [
                                                'class' => 'btn btn-primary pull-right'
                                            ]
                                        ) ?>
                                    </div>
                                    <?php $form->end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
