<?php
use yii\helpers\Html;
use yii\helpers\Url;

use bl\cms\partner\frontend\controllers\DefaultController as Controller;
use bl\cms\partner\frontend\Module as PartnerModule;

/**
 * View file for default controller in frontend module
 *
 * @var \yii\web\View $this
 * @var integer $status
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$loginLink = Html::a(
    PartnerModule::t('request', 'login'),
    Url::to(Yii::getAlias('@signIn'))
);
$registerLink = Html::a(
    PartnerModule::t('request', 'register'),
    Url::to(Yii::getAlias('@signUp'))
);

$message =  PartnerModule::t('request', 'For sending a partner request you should to {login} or {register}', [
    'login'    => $loginLink,
    'register' => $registerLink
]);

switch ($status) {
    case Controller::REQUEST_STATUS_ACCEPT_LEGAL_AGREEMENT:
        $linkToAgreement = Html::a(
            PartnerModule::t('request', 'legal agreement'),
            Url::toRoute([
                '/legal',
                'legalId' => 1,
                'langId' => 1
            ])
        );

        $message = PartnerModule::t(
            'request',
            'For sending your request to moderation you should to accept the {link-to-legal-agreement}',
            ['link-to-legal-agreement' => $linkToAgreement]
        );
        break;
    case Controller::REQUEST_STATUS_SEND_TO_MODERATION:
        $message = PartnerModule::t('request', 'Your request has been send to pg-pool manager');
        break;
    case Controller::REQUEST_STATUS_ACCEPTED:
        $message = PartnerModule::t('request', 'You already a partner');
        break;
}
?>

<div class="col-md-12">
    <h1 class="text-center">
        <?= Html::encode(PartnerModule::t('request', 'Partner request')) ?>
    </h1>
    <p class="text-center">
        <?= $message ?>
    </p>
    <?= \bl\cms\partner\frontend\widgets\PersonalAreaNav::widget(['companyId' => 10]) ?>
</div>
