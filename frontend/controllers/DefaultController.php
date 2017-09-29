<?php
namespace bl\cms\partner\frontend\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

use bl\cms\partner\frontend\models\forms\PartnerRequest;
use bl\cms\partner\common\entities\ModerationStatus;
use bl\cms\partner\common\interfaces\StatusInterface;

use bl\multilang\entities\Language;

/**
 * Default controller for Partner frontend module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class DefaultController extends Controller
{
    const REQUEST_STATUS_ACCEPT_LEGAL_AGREEMENT = 1;
    const REQUEST_STATUS_SEND_TO_MODERATION = 2;
    const REQUEST_STATUS_ACCEPTED = 3;


    /**
     * @var \bl\cms\partner\frontend\Module
     */
    public $module;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'send-request' => ['post']
                ]
            ],
        ];
    }

    /**
     * Main action in partner module
     *
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            if (!$this->module->getUserComponent()->isUserPartner()) {
                return $this->redirect(Url::toRoute(['request']));
            }
            else if ($this->module->getUserComponent()->isUserAcceptedPartner(Yii::$app->user->id)){
                return $this->redirect(Url::toRoute(['/partner/company/edit']));
            }
            else if ($this->module->getUserComponent()->isUserEmployee(Yii::$app->user->id)) {
                return $this->redirect(Url::toRoute(['/partner/company/view']));
            }
        }

        /** @var StatusInterface $request */
        $request = $this->module->getUserComponent()->getRequest();

        $status = null;
        switch ($request->statusCode()) {
            case ModerationStatus::STATUS_ON_CONFIRMATION:
               $status = self::REQUEST_STATUS_ACCEPT_LEGAL_AGREEMENT;
                break;
            case ModerationStatus::STATUS_ON_MODERATION:
                $status = self::REQUEST_STATUS_SEND_TO_MODERATION;
                break;
            case ModerationStatus::STATUS_ACCEPTED:
                $status = self::REQUEST_STATUS_ACCEPTED;
                break;
        }

        Yii::$app->getUser()->setReturnUrl('/partner');

        return $this->render('index', [
            'status' => $status
        ]);
    }

    /**
     * Render partner request form
     *
     * @return string|\yii\web\Response
     */
    public function actionRequest()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect('index');
        }

        $model = Yii::createObject(PartnerRequest::class);

        $langId = Language::getCurrent()->id;
        $legalId = $this->module->getLegalManager()->getByKey(
            'partner',
            $langId
        )->id;

        return $this->render('form', [
            'model' => $model,
            'langId' => $langId,
            'legalId' => $legalId
        ]);
    }

    /**
     * Send request for the partner
     *
     * @return \yii\web\Response|array
     */
    public function actionSendRequest()
    {
        /** @var PartnerRequest $model */
        $model = Yii::createObject(PartnerRequest::class);

        $request = Yii::$app->getRequest();
        if ($model->load($request->post()) && $model->save()) {
            // TODO: validation errors
            $this->module->getMailer()->sendLegalAgreement(
                Yii::$app->user->id,
                Yii::$app->user->identity->email
            );
        }
        elseif ($request->getIsAjax() && $model->load($request->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        return $this->redirect(['/partner']);
    }
}
