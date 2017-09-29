<?php
namespace bl\cms\partner\frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\common\entities\MaterialCategory;
use bl\cms\partner\common\entities\Material;

/**
 * Materials controller for Partner frontend module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class MaterialsController extends Controller
{
    /**
     * @var PartnerModule
     */
    public $module;


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['download'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['download'],
                        'roles' => ['employee']
                    ]
                ]
            ]
        ];
    }

    /**
     * Render material categories with files
     *
     * @return string
     */
    public function actionIndex()
    {
        $materialCategories = MaterialCategory::find()
            ->with('materials')
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->all();

        return $this->render('index', [
            'materialCategories' => $materialCategories,
            'fileManager' => $this->module->getFileManager()
        ]);
    }

    /**
     * Downloading the material file
     *
     * @param integer $id material file ID
     * @return \yii\web\Response
     */
    public function actionDownload($id)
    {
        if ($material = Material::findOne($id)) {
            /** @var \bl\cms\partner\common\components\FileManager $fileManager */
            $fileManager = $this->module->getFileManager();

            return Yii::$app->response->sendFile(
                $fileManager->getPathToFile($material->fileName),
                $material->fileName
            );
        }

        return $this->redirect(Yii::$app->getRequest()->getReferrer());
    }
}
