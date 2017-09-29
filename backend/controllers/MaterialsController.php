<?php
namespace bl\cms\partner\backend\controllers;

use Exception;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

use bl\cms\partner\backend\Module as PartnerModule;
use bl\cms\partner\backend\models\MaterialFile;
use bl\cms\partner\common\entities\MaterialCategory;
use bl\cms\partner\common\entities\MaterialCategoryTranslation;
use bl\cms\partner\common\entities\Material;
use bl\cms\partner\common\entities\MaterialTranslation;

use bl\multilang\entities\Language;

/**
 * Materials controller for Partner backend module
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
            'verbs' => [
                'class' => VerbFilter::class,'actions' => [
                  'add-material'  => ['post'],
              ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'create',
                    'edit',
                    'delete',
                    'add-material',
                    'edit-material',
                    'delete-material',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'create',
                            'edit',
                            'delete',
                            'add-material',
                            'edit-material',
                            'delete-material',
                        ],
                        'roles' => ['managePartnerMaterials']
                    ]
                ]
            ],
        ];
    }

    /**
     * Renders list of categories with action buttons
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'categories' => MaterialCategory::find()->all()
        ]);
    }

    /**
     * Create new category
     *
     * @param null|integer $languageId
     * @return string|\yii\web\Response
     * @throws Exception
     */
    public function actionCreate($languageId = null)
    {
        $model = new MaterialCategoryTranslation();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {
            $category = new MaterialCategory();

            $transaction = MaterialCategory::getDb()->beginTransaction();
            try {
                $category->insert(false);
                $model->categoryId = $category->id;
                $model->insert();

                $transaction->commit();

                return $this->redirect(['/partner/materials']);
            }
            catch (Exception $ex) {
                $transaction->rollBack();
                throw $ex;
            }
        }

        return $this->render('create', [
            'model' => $model,
            'languageId' => $languageId
        ]);
    }

    /**
     * Edit category and material files
     *
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionEdit($id)
    {
        $languageId = Language::getCurrent()->id;

        $category = MaterialCategoryTranslation::findOne([
            'categoryId' => $id,
            'languageId' => $languageId
        ]);

        if (is_null($category)) {
            $category = new MaterialCategoryTranslation([
                'categoryId' => $id,
                'languageId' => $languageId
            ]);
        }

        if (Yii::$app->request->isPost && $category->load(Yii::$app->request->post())) {
            $category->update();

            return $this->redirect(['/partner/materials']);
        }

        /** @var Material[] $materials */
        $materials = Material::findAll(['categoryId' => $id]);

        return $this->render('edit', [
            'model' => $category,
            'materials' => $materials
        ]);
    }

    /**
     * Delete category with all material files
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDelete($id)
    {
        if ($category = MaterialCategory::findOne($id)) {
            $category->delete();
        }

        return $this->redirect(['/partner/materials']);
    }

    /**
     * Add material file to category
     *
     * @param integer $categoryId
     * @return string
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionAddMaterial($categoryId)
    {
        if (Yii::$app->request->isAjax) {
            /** @var UploadedFile[] $files */
            $files = UploadedFile::getInstancesByName('materialFiles');
            $fileManager = $this->module->getFileManager();

            $errors = [];
            foreach ($files as $file) {
                /** @var MaterialFile $model */
                $model = new MaterialFile($fileManager->filePrefix, $fileManager->filesRoot);
                $model->file = $file;

                if ($fileName = $model->upload()) {
                    $transaction = Material::getDb()->beginTransaction();
                    try {
                        $material = new Material([
                            'categoryId' => $categoryId,
                            'fileName' => StringHelper::basename($fileName)
                        ]);
                        $material->insert();

                        $translation = new MaterialTranslation([
                            'materialId' => $material->id,
                            'languageId' => Language::getCurrent()->id,
                            'title' => $material->fileName
                        ]);
                        $translation->insert();

                        $transaction->commit();
                    }
                    catch (Exception $ex) {
                        $transaction->rollBack();
                        throw $ex;
                    }
                }
                else {
                    $errors[] = $model->getErrors();
                }
            }

            return Json::encode($errors);
        }

        throw new NotFoundHttpException();
    }

    /**
     * Delete material from DB and file system
     *
     * @param integer $id
     * @return \yii\web\Response
     */
    public function actionDeleteMaterial($id)
    {
        if ($material = Material::findOne($id)) {
            $this->module->getFileManager()->deleteFile($material->fileName);
            $material->delete();
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Edit material title
     *
     * @param integer $id
     * @return string|\yii\web\Response
     */
    public function actionEditMaterial($id)
    {
        $languageId = Language::getCurrent()->id;

        $material = MaterialTranslation::findOne([
            'materialId' => $id,
            'languageId' => $languageId
        ]);

        if (is_null($material)) {
            $material = new MaterialTranslation([
                'materialId' => $id,
                'languageId' => $languageId
            ]);
        }

        if (Yii::$app->request->isPost && $material->load(Yii::$app->request->post())) {
            $material->save();

            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->render('edit-material', [
            'model' => $material
        ]);
    }
}
