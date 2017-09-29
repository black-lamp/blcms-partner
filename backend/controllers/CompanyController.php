<?php
namespace bl\cms\partner\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

use bl\cms\partner\common\entities\CompanyStatus;
use bl\cms\partner\common\entities\PartnerCompany;
use bl\cms\partner\common\entities\CompanyEmployee;

use bl\cms\cart\common\components\user\models\Profile;

/**
 * Company controller for Partner backend module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyController extends Controller
{
    /**
     * @var \bl\cms\partner\backend\Module
     */
    public $module;
    /**
     * @inheritdoc
     */
    public $defaultAction = 'list';


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'hire-employee' => ['post']
                ]
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'list',
                    'view',
                    'ban',
                    'un-ban',
                    'hire-employee',
                    'fire-employee'
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'view',
                            'list',
                        ],
                        'roles' => ['viewPartnerCompanies']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'ban',
                            'un-ban',
                        ],
                        'roles' => ['blockPartnerCompany']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'hire-employee',
                            'fire-employee'
                        ],
                        'roles' => ['editPartnerCompany']
                    ]
                ]
            ],
        ];
    }

    /**
     * Render list of the companies
     *
     * @return string
     */
    public function actionList()
    {
        $query = PartnerCompany::find()
            ->select(['id', 'ownerId', 'name', 'officialName', 'siteLink', 'createdAt'])
            ->with(['owner', 'employees']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'attributes' => ['name', 'officialName', 'createdAt'],
                'defaultOrder' => [
                    'name' => SORT_ASC,
                    'officialName' => SORT_ASC,
                    'createdAt' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('list', [
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Render all information about company by ID
     *
     * @param integer $id company ID
     * @return string
     */
    public function actionView($id)
    {
        /** @var PartnerCompany $company */
        $company = PartnerCompany::find()
            ->where(['id' => $id])
            ->with([
                'owner',
                'employees',
                'info',
                'status'
            ])
            ->one();

        $isBlocked = ($company->statusId == CompanyStatus::STATUS_BLOCKED) ? true : false;

        $employees = CompanyEmployee::find()
            ->where(['companyId' => $id])
            ->with('user')
            ->all();

        $filterUsers = CompanyEmployee::find()
            ->select('userId')
            ->all();
        $users = Profile::find()
            ->where(['not in', 'user_id', ArrayHelper::getColumn($filterUsers, 'userId')])
            ->all();

        return $this->render('view', [
            'company' => $company,
            'isBlocked' => $isBlocked,
            'users' => $users,
            'employees' => $employees
        ]);
    }

    /**
     * Ban company by ID
     *
     * @param integer $id company ID
     * @return \yii\web\Response
     */
    public function actionBan($id)
    {
        PartnerCompany::findOne($id)->ban();

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * Un ban company by ID
     *
     * @param integer $id company ID
     * @return \yii\web\Response
     */
    public function actionUnBan($id)
    {
        PartnerCompany::findOne($id)->unBan();

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }

    /**
     * Hire employee to the company
     *
     * @return \yii\web\Response
     */
    public function actionHireEmployee()
    {
        $request = Yii::$app->getRequest();
        $post = $request->post();

        $this->module->getCompanyManager()
            ->hireEmployee($post['companyId'], $post['employeeId']);

        return $this->redirect($request->getReferrer());
    }

    /**
     * Fire employee from company
     *
     * @param integer $id Employee id
     * @return \yii\web\Response
     */
    public function actionFireEmployee($id)
    {
        $this->module->getCompanyManager()->fireEmployee($id);

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }
}
