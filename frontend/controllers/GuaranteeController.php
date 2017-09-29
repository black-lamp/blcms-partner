<?php
namespace bl\cms\partner\frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use bl\cms\partner\frontend\models\forms\GuaranteeRegistration;

/**
 * Guarantee controller for Partner frontend module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class GuaranteeController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['employee']
                    ]
                ]
            ]
        ];
    }

    /**
     * Render form guarantee registration form
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new GuaranteeRegistration();

        if (Yii::$app->getRequest()->getIsPost() && $model->load(Yii::$app->request->post())) {
            if ($model->send()) {
                Yii::$app->getSession()->setFlash(
                    'success',
                    \Yii::t('partner', 'Your request for guarantee registration was successfully send')
                );
                $this->refresh();
            }
        }

        return $this->render('index', [
            'model' => $model,
            'message' => Yii::$app->getSession()->getFlash('partner')
        ]);
    }
}
