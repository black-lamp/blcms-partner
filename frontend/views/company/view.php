<?php
use yii\helpers\Html;

use bl\cms\partner\frontend\widgets\PersonalAreaNav;

/**
 * View file for company controller in Partner frontend module
 *
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\PartnerCompany $company
 * @var \bl\cms\partner\common\entities\CompanyInfo[] $emails
 * @var \bl\cms\partner\common\entities\CompanyInfo[] $phones
 * @var string $imagesRootUrl
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = Yii::t('company', 'Company info');
?>

<div class="row">
    <div class="col-md-3">
        <?= PersonalAreaNav::widget() ?>
    </div>
    <div class="col-md-6">
        <h1 class="text-center">
            <?= $this->title ?>
        </h1>
        <div>
            <dl>
                <dt>
                    <?= Yii::t('company.info', 'Name') ?>
                </dt>
                <dd>
                    <?= Html::encode($company->name) ?>
                </dd>
            </dl>
            <dl>
                <dt>
                    <?= Yii::t('company.info', 'Official name') ?>
                </dt>
                <dd>
                    <?= Html::encode($company->officialName) ?>
                </dd>
            </dl>
            <dl>
                <dt>
                    <?= Yii::t('company.info', 'Site') ?>
                </dt>
                <dd>
                    <?= Html::a($company->siteLink, $company->siteLink) ?>
                </dd>
            </dl>
            <dl>
                <dt>
                    <?= Yii::t('company.info', 'Logo') ?>
                </dt>
                <dd>
                    <?= Html::img($company->logo, ['width' => 200]) ?>
                </dd>
            </dl>
            <dl>
                <dt>
                    <?= Yii::t('company.info', 'Emails') ?>
                </dt>
                <dd>
                    <?php foreach ($emails as $email): ?>
                        <p>
                            <?= Html::mailto($email->content, $email->content) ?>
                        </p>
                    <?php endforeach; ?>
                </dd>
            </dl>
            <dl>
                <dt>
                    <?= Yii::t('company.info', 'Phones') ?>
                </dt>
                <dd>
                    <?php foreach ($phones as $phone): ?>
                        <p>
                            <?= $phone->content ?>
                        </p>
                    <?php endforeach; ?>
                </dd>
            </dl>
        </div>
    </div>
</div>
