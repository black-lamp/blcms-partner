<?php
namespace bl\cms\partner\frontend\controllers;

use bl\cms\partner\common\base\PartnerModule;
use bl\cms\partner\frontend\models\forms\SubsiteRequestForm;
use dektrium\user\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

use bl\cms\partner\frontend\models\forms\EditCompanyInfo;
use bl\cms\partner\common\entities\PartnerCompany;
use bl\cms\partner\common\entities\CompanyInfo;
use bl\cms\partner\frontend\models\forms\EmployeeRequest;
use bl\cms\partner\common\entities\CompanyEmployee;

use bl\cms\cart\common\components\user\models\Profile;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Company controller for Partner frontend module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyController extends Controller
{
    /**
     * @var \bl\cms\partner\frontend\Module
     */
    public $module;
    /**
     * @inheritdoc
     */
    public $defaultAction = 'edit';

    /**
     * @var integer
     */
    private $companyId;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['view', 'edit', 'employees', 'send-request'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['employee']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['edit', 'employees', 'send-request'],
                        'roles' => ['director']
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'send-request' => ['post']
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->companyId = $this->module->getUserComponent()->getCompanyId();
    }

    /**
     * View company information
     *
     * @return string
     */
    public function actionView()
    {
        $company = PartnerCompany::findOne($this->companyId);

        return $this->render('view', [
            'company' => $company,
            'emails' => CompanyInfo::getEmails($this->companyId),
            'phones' => CompanyInfo::getPhones($this->companyId),
            'imagesRootUrl' => $this->module->imagesRootUrl
        ]);
    }


    /**
     * Edit company information
     *
     * @return string
     */
    public function actionEdit()
    {
        $company = PartnerCompany::findOne($this->companyId);
        $model = EditCompanyInfo::getFromCompany($company);

        $request = Yii::$app->getRequest();
        if ($request->getIsPost() && $model->load($request->post())) {
            if ($model->save()) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    \Yii::t('partner', 'New information about company was successfully saved')
                );

                return $this->refresh();
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'imagesRootUrl' => $this->module->imagesRootUrl,
            'message' => Yii::$app->getSession()->getFlash('partner')
        ]);
    }

    /**
     * @return string
     */
    public function actionEmployees()
    {
        $employees = CompanyEmployee::find()
            ->where(['companyId' => $this->companyId])
            ->with('user.profile')
            ->all();

        $users = Profile::find()->where('user_id' != Yii::$app->getUser()->getId());
        if (!empty($employees)) {
            $users = $users->andWhere('user_id' != ArrayHelper::getColumn($employees, 'userId'));
        }
        $users = $users->with('user')->all();

        return $this->render('employees', [
            'users' => $users,
            'companyId' => $this->companyId,
            'employees' => $employees,
            'message' => Yii::$app->getSession()->getFlash('partner')
        ]);
    }

    /**
     * Send request for employee to moderation
     *
     * @return \yii\web\Response
     */
    public function actionSendRequest()
    {
        if (\Yii::$app->request->isPost) {
            $request = Yii::$app->getRequest();
            $post = $request->post();

            //Finds user by email which was send in post
            $userModel = new User();
            $user = $userModel->finder->findUserByEmail($post['employeeId']);

            //If user has already exist in some company do not add him
            if (!empty($user)) {
                if (!CompanyEmployee::find()->select(['id'])->asArray()->where(['userId' => $user->id])->one()) {

                    /** @var EmployeeRequest $model */
                    $model = new EmployeeRequest([
                        'companyId' => $post['companyId'],
                        'userId' => $user->id
                    ]);

                    if ($model->send()) {
                        Yii::$app->getSession()->setFlash(
                            'success',
                            Yii::t('partner', 'Request for adding a employee was successfully sended to moderation')
                        );
                    }

                }
                else {
                    Yii::$app->getSession()->setFlash(
                        'success',
                        Yii::t('partner.employees', 'Such user can not be added')
                    );
                }
            }
            else {
                Yii::$app->getSession()->setFlash(
                    'error',
                    Yii::t('partner.employees', 'Unfortunately, this user does not exist')
                );
            }
        }

        return $this->redirect(\Yii::$app->request->getReferrer());
    }

    // TODO: PHPDoc
    public function actionSiteRequest()
    {
        $pgModel = SubsiteRequestForm::buildById(
            $this->companyId,
            SubsiteRequestForm::SCENARIO_PG_POOL_DOMAIN
        );
        $personalModel = SubsiteRequestForm::buildById(
            $this->companyId,
            SubsiteRequestForm::SCENARIO_MY_DOMAIN
        );

        return $this->render('site-request', [
            'pgModel' => $pgModel,
            'personalModel' => $personalModel
        ]);
    }

    // TODO: PHPDoc
    public function actionSendSubsiteRequest()
    {
        $model = SubsiteRequestForm::buildById(
            $this->companyId,
            SubsiteRequestForm::SCENARIO_PG_POOL_DOMAIN
        );
        $request = Yii::$app->getRequest();

        if ($request->getIsAjax() && $model->load($request->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        elseif ($model->load($request->post())) {
            if ($model->save()) {
                $this->module->getMailer()->requestForSiteCreationNotification($model);
            }
        }

        return $this->redirect($request->getReferrer());
    }

    // TODO: PHPDoc
    public function actionSendSiteRequest()
    {
        $model = SubsiteRequestForm::buildById(
            $this->companyId,
            SubsiteRequestForm::SCENARIO_MY_DOMAIN
        );

        $request = Yii::$app->getRequest();
        if ($model->load($request->post())) {

            if ($model->save()) {

                Yii::$app->getSession()->setFlash(
                    'success',
                    \Yii::t('partner', 'Your request for website creation was sent successfully')
                );
                $this->module->getMailer()->requestForSiteCreationNotification($model);
            }
        }

        return $this->redirect($request->getReferrer());
    }
}
