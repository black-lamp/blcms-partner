<?php
namespace bl\cms\partner\common\components;

use bl\legalAgreement\common\entities\Legal;
use Yii;
use yii\base\Object;
use yii\helpers\Html;
use yii\helpers\Url;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\common\entities\PartnerCompany;
use bl\cms\partner\common\entities\EmployeeRequest;
use bl\cms\partner\frontend\models\forms\SubsiteRequestForm;
use backend\helpers\FrontendUrl;

use bl\multilang\entities\Language;
use bl\emailTemplates\components\TemplateManager;
use bl\emailTemplates\data\Template;
use bl\cms\cart\common\components\user\models\Profile;
use bl\cms\shop\common\components\user\models\User;

/**
 * Mailer component for Partner module
 *
 * @property TemplateManager $templateManager
 * @property string $from
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class PartnerMailer extends Object
{
    /**
     * @var TemplateManager
     */
    public $templateManager;
    /**
     * @var string Email from will be send letters
     */
    public $from;


    /**
     * @inheritdoc
     */
    public function __construct(TemplateManager $templateManager, $config = [])
    {
        $this->templateManager = $templateManager;
        $this->from = Yii::$app->params['infoEmail'];

        parent::__construct($config);
    }

    /**
     * Get template by key
     *
     * @param string $key template key
     * @return Template
     */
    private function getTemplate($key)
    {
        $language = Language::getCurrent()->id;
        return $this->templateManager->getByLangOrFirst($key, $language);
    }

    /**
     * Sending the letter to email from email template
     *
     * @param string $from
     * @param string $to user email
     * @param Template $template
     * @return bool return `true` if the letter was successfully send
     */
    public static function send($from, $to, $template)
    {
        return Yii::$app->get('partnerMailer')->compose()
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($template->getSubject())
            ->setHtmlBody($template->getBody())
            ->send();
    }

    /**
     * Send the legal agreement to email
     *
     * @param integer $userId
     * @param string $email user email
     * @return bool return `true` if the letter was successfully send
     */
    public function sendLegalAgreement($userId, $email)
    {
        /** @var \bl\legalAgreement\common\components\LegalManager $legalComponent */
        $legalComponent = Yii::$app->get('legal');
        $template = $this->getTemplate('legal-agreement');

        $legalAgreement = Legal::findOne(['key' => 'partners']);
        $acceptLink = Html::a(
            PartnerModule::t('partner.email', 'legal agreement'),
            Url::toRoute([
                '/legal/default/accept',
                'legalId' => $legalAgreement->id,
                'token' => $legalComponent->generateToken($legalAgreement->id, $userId)
            ], true)
        );

        $addEmployeesLink = Html::a(
            PartnerModule::t('partner.email', 'add employees'),
            PartnerModule::toRoute('company/employees', true)
        );

        $marketingMaterialsLink = Html::a(
            PartnerModule::t('partner.email', 'materials'),
            PartnerModule::toRoute('materials', true)
        );

        $template->parseBody([
            '{accept-link}' => $acceptLink,
            '{add-employees}' => $addEmployeesLink,
            '{marketing-materials}' => $marketingMaterialsLink
        ]);

        return static::send($this->from, $email, $template);
    }

    /**
     * Send the legal agreement for company employee to email
     *
     * @param integer $userId
     * @param string $email user email
     * @return bool return `true` if the letter was successfully send
     */
    public function sendLegalAgreementForEmployee($userId, $email)
    {
        /** @var \bl\legalAgreement\common\components\LegalManager $legalComponent */
        $legalComponent = Yii::$app->get('legal');
        $template = $this->getTemplate('legal-agreement-employees');

        $companyId = EmployeeRequest::find()
            ->select('companyId')
            ->where(['userId' => $userId])
            ->scalar();
        $companyName = PartnerCompany::find()
            ->select('name')
            ->where(['id' => $companyId])
            ->column();

        $legalAgreement = Legal::findOne(['key' => 'partners']);
        $acceptLink = Html::a(
            PartnerModule::t('partner.email', 'legal agreement'),
            FrontendUrl::toRoute([
                '/legal/default/accept',
                'legalId' => $legalAgreement->id,
                'token' => $legalComponent->generateToken($legalAgreement->id, $userId)
            ], true)
        );

        $template->parseSubject([
            '{company-name}' => $companyName[0]
        ]);
        $template->parseBody([
            '{agreement-link}' => $acceptLink,
            '{company-name}' => $companyName[0]
        ]);

        return static::send($this->from, $email, $template);
    }

    /**
     * Information letter for user after partner moderation
     *
     * @param string $email user email
     * @param bool $isSuccess
     * @return bool return `true` if the letter was successfully send
     */
    public function sendAfterModerationMessage($email, $isSuccess)
    {
        $key = ($isSuccess) ? 'partner-after-moderation' : 'partner-after-moderation-decline';
        $template = $this->getTemplate($key);

        if ($isSuccess) {
            $linkToPersonalArea = Html::a(
                Yii::t('partner.email', 'personal area'),
                \Yii::$app->get('urlManagerFrontend')->createAbsoluteUrl(['/partner'])
            );

            $template->parseBody([
                '{personal-area-link}' => $linkToPersonalArea
            ]);
        }

        return static::send($this->from, $email, $template);
    }

    /**
     * Notification about new user in company for company owner
     *
     * @param string $email user email
     * @param integer $employeeId
     * @return bool return `true` if the letter was successfully send
     */
    public function sendAddedEmployeeNotification($email, $employeeId)
    {
        $template = $this->getTemplate('company-owner-add-employee');

        $employeesListLink = Html::a(
            PartnerModule::t('partner.email', 'employee list'),
            PartnerModule::toRoute('company/employees', true)
        );
        /** @var Profile $user */
        $user = Profile::find()
            ->select(['name', 'surname', 'patronymic'])
            ->where(['user_id' => $employeeId])
            ->one();
        $userName = $user->getUserNameWithSurname();

        $template->parseSubject([
            '{employee-name}' => $userName
        ]);
        $template->parseBody([
            '{employee-list-link}' => $employeesListLink
        ]);

        return static::send($this->from, $email, $template);
    }

    /**
     * Guarantee data letter
     *
     * @param string $name
     * @param string $sku
     * @param string $companyName
     * @param string $blankNumber
     * @return bool return `true` if the letter was successfully send
     */
    public function sendGuaranteeData($name, $sku, $companyName, $blankNumber)
    {
        $template = $this->getTemplate('guarantee-form');

        $template->parseBody([
            '{name}' => $name,
            '{sku}' => $sku,
            '{company-name}' => $companyName,
            '{blank-number}' => $blankNumber
        ]);

        return static::send($this->from, Yii::$app->params['guaranteeEmail'], $template);
    }

    /**
     * @param integer $companyId
     * @param integer $userId
     * @return bool return `true` if the letter was successfully send
     */
    public function sendRequestEmployeeNotification($companyId, $userId)
    {
        $template = $this->getTemplate('add-employee-notification');

        /** @var PartnerCompany $company */
        $company = PartnerCompany::find()
            ->select(['ownerId', 'name'])
            ->where(['id' => $companyId])
            ->one();
        $owner = User::find()
            ->select('email')
            ->where(['id' => $company->ownerId])
            ->column();
        $employee = User::find()
            ->select('email')
            ->where(['id' => $userId])
            ->column();

        $companyLink = Html::a($company->name, Url::toRoute([
            '/admin/partner/company/view',
            'id' => $company->id
        ], true));
        $ownerLink = Html::a($owner[0], Url::toRoute([
            '/admin/user/admin/update',
            'id' => $company->ownerId
        ], true));
        $employeeLink = Html::a($employee[0], Url::toRoute([
            '/admin/user/admin/update',
            'id' => $userId
        ], true));
        $moderationLink = Html::a(
            PartnerModule::t('partner.email', 'Show'),
            Url::toRoute(['/admin/partner/moderation/employee-list'], true)
        );

        $template->parseSubject([
            '{employee-name}' => $employeeLink,
            '{company-name}' => $companyLink
        ]);

        $template->parseBody([
            '{employee-name}' => $employeeLink,
            '{company-name}' => $companyLink,
            '{company-owner}' => $ownerLink,
            '{moderation-link}' => $moderationLink
        ]);

        return static::send($this->from, Yii::$app->params['adminEmail'], $template);
    }

    /**
     * @param SubsiteRequestForm $request
     */
    public function requestForSiteCreationNotification($request)
    {
        /** @var PartnerCompany $company */
        $company = PartnerCompany::find()
            ->select(['id', 'name'])
            ->where(['id' => $request->companyId])
            ->one();
        $companyLink = Html::a($company->name, Url::toRoute([
            '/admin/partner/company/view',
            'id' => $company->id
        ], true));

        $body = PartnerModule::t('partner.email', 'Company: {company-link}', [
            'company-link' => $companyLink
        ]);
        $body .= PartnerModule::t('partner.email', 'Site name: {name}', [
            'name' => $request->domainName
        ]);
        $body .= PartnerModule::t('partner.email', 'Is has hosting: {has-hosting}', [
            'has-hosting' => ($request->hasHosting === null) ?
                Yii::t('partner.email', 'I want to create my site in pg-pool.com') :
                ($request->hasHosting) ? 'yes' : 'no'

        ]);

        return Yii::$app->get('partnerMailer')->compose()
            ->setFrom($this->from)
            ->setTo([Yii::$app->params['adminEmail'], Yii::$app->params['supportEmail']])
            ->setSubject(PartnerModule::t('partner.email', 'Site creation request'))
            ->setHtmlBody($body)
            ->send();
    }

    // TODO: PHPDoc
    public function sendEmployeeConfirmedNotification($employeeId)
    {
        $template = $this->getTemplate('employee-confirmed');

        /** @var Profile $user */
        $user = Profile::find()
            ->select(['name', 'surname', 'patronymic'])
            ->where(['user_id' => $employeeId])
            ->one();
        $employee = $user->getUserNameWithSurname();

        $employeeListLink = Html::a(
            PartnerModule::t('partner.email', 'Employee list'),
            Url::toRoute(['/partner/company/employees'], true)
        );

        $template->parseBody([
            '{employee-name}' => $employee,
            '{employee-list-link}' => $employeeListLink
        ]);
    }
}