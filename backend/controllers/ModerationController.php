<?php
namespace bl\cms\partner\backend\controllers;

use bl\cms\partner\common\entities\PartnerCompany;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;

use bl\cms\partner\backend\Module as PartnerModule;
use bl\cms\partner\common\entities\UserRequest;
use bl\cms\partner\backend\events\AfterModerationAccept;
use bl\cms\partner\common\entities\EmployeeRequest;

use bl\cms\shop\common\components\user\models\User;

/**
 * Moderation controller for Partner backend module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class ModerationController extends Controller
{
    /**
     * @var PartnerModule
     */
    public $module;
    /**
     * @inheritdoc
     */
    public $defaultAction = 'partners-list';


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'partners-list',
                    'accept',
                    'decline',
                    'employee-list',
                    'accept-employee',
                    'decline-employee'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'partners-list',
                            'accept',
                            'decline',
                            'employee-list',
                            'accept-employee',
                            'decline-employee'
                        ],
                        'roles' => ['moderatePartnerEmployees']
                    ]
                ]
            ],
        ];
    }

    /**
     * List with requests for partners
     *
     * @return string
     */
    public function actionPartnersList()
    {
        $requests = UserRequest::find()
            ->onModeration()
            ->with(['user'])
            ->all();

        $message = Yii::$app->getSession()->getFlash('partner.moderation');

        return $this->render('partners-list', [
            'requests' => $requests,
            'message' => $message
        ]);
    }

    /**
     * Send after moderation message to user email
     *
     * @param string $email
     * @param bool $isSuccess
     */
    private function afterModerationMessage($email, $isSuccess) {
        $this->module->getMailer()->sendAfterModerationMessage($email, $isSuccess);
    }

    /**
     * Accept request for partner
     *
     * @param integer $requestId
     * @return \yii\web\Response
     */
    public function actionAccept($requestId)
    {
        $request = UserRequest::findOne($requestId);
        $request->setStatusAccepted();

        if ($id = $this->module->getCompanyManager()->createCompany($request)) {
            $userEmail = User::findOne($request->userId)->email;
            $this->afterModerationMessage($userEmail, true);

            $message = PartnerModule::t(
                'moderation',
                'Company was successfully created! See {company-card-link}',
                [
                    'company-card-link' => Html::a(
                        PartnerModule::t('moderation', 'company information'),
                        Url::toRoute(['company/view', 'id' => $id]),
                        [
                            'class' => 'alert-link',
                            'target' => '_blank'
                        ]
                    )
                ]
            );
            Yii::$app->getSession()->setFlash('partner.moderation', $message);

            $this->trigger(AfterModerationAccept::EVENT_AFTER_MODERATION_ACCEPT, new AfterModerationAccept([
                'partnerId' => $request->userId
            ]));
        }

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * Decline request for partner
     *
     * @param integer $requestId
     * @return \yii\web\Response
     */
    public function actionDecline($requestId)
    {
        $request = UserRequest::findOne($requestId);
        $request->setStatusDecline();

        $userEmail = User::findOne($request->userId)->email;
        $this->afterModerationMessage($userEmail, false);

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * List with requests for employees
     *
     * @return string
     */
    public function actionEmployeeList()
    {
        $requests = EmployeeRequest::find()
            ->onModeration()
            ->with(['user', 'company'])
            ->all();

        return $this->render('employees-list', [
            'requests' => $requests
        ]);
    }

    /**
     * Accept request for employee
     *
     * @param integer $requestId
     * @return \yii\web\Response
     */
    public function actionAcceptEmployee($requestId)
    {
        $request = EmployeeRequest::findOne($requestId);
        $request->setStatusOnConfirmation();

        $mailer = $this->module->getMailer();

        $user = User::findOne($request->userId);
        $mailer->sendLegalAgreementForEmployee($user->id, $user->email);

        $ownerId = PartnerCompany::find()
            ->select('ownerId')
            ->where(['id' => $request->companyId])
            ->scalar();
        $owner = User::findOne($ownerId);
        $mailer->sendAddedEmployeeNotification($owner->email, $request->userId);

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * Decline request for add employee to the company
     *
     * @param integer $requestId
     * @return \yii\web\Response
     */
    public function actionDeclineEmployee($requestId)
    {
        $request = EmployeeRequest::findOne($requestId);
        $request->setStatusDecline();

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }
}
