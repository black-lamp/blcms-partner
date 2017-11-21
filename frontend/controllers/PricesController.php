<?php
namespace bl\cms\partner\frontend\controllers;

use bl\cms\shop\common\components\user\models\UserGroup;
use bl\cms\shop\common\entities\Currency;
use bl\cms\shop\common\entities\Product;
use PHPExcel;
use PHPExcel_Style_Alignment;
use PHPExcel_Worksheet;
use PHPExcel_Writer_Excel2007;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

use bl\cms\partner\backend\Module as PartnerModule;

/**
 * @author Vadim Gutsulyak
 */
class PricesController extends Controller
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
                'class' => VerbFilter::class, 'actions' => [
                    'load' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'only' => [
                    'index',
                    'load',
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'load',
                        ],
                        'roles' => ['loadPrices']
                    ]
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionLoad()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $rowCount = 1;
        $activeSheet->getColumnDimension('A')->setWidth(11);
        $activeSheet->getColumnDimension('B')->setWidth(12);
        $activeSheet->getColumnDimension('C')->setWidth(100);
        $activeSheet->getColumnDimension('D')->setWidth(14);
        $activeSheet->getColumnDimension('E')->setWidth(20);
        $activeSheet->getColumnDimension('F')->setWidth(14);
        $activeSheet->getColumnDimension('G')->setWidth(16);
        $activeSheet->getColumnDimension('H')->setWidth(7);
        $activeSheet->getColumnDimension('I')->setWidth(10);

        $activeSheet->SetCellValue('A1', 'Постачальник');
        $activeSheet->SetCellValue('B1', 'Артикул');
        $activeSheet->SetCellValue('C1', 'Найменування товару');
        $activeSheet->SetCellValue('D1', 'Категорія');
        $activeSheet->SetCellValue('E1', 'Наявність');
        $activeSheet->SetCellValue('F1', 'Ціна РРЦ (грн.)');
        $activeSheet->SetCellValue('G1', 'Ціна дилерська (грн.)');
        $activeSheet->SetCellValue('H1', 'Курс грн:');
        $activeSheet->SetCellValue('I1', Currency::currentCurrency());
        $activeSheet->SetCellValue('H2', 'Дата:');
        $activeSheet->SetCellValue('I2', date('d.m.Y'));

        /* @var $products Product[] */
        $products = Product::find()
            ->where([
                'status' => Product::STATUS_SUCCESS,
                'show' => true
            ])
            ->with(['category', 'combinations'])
            ->orderBy(['category_id' => SORT_ASC])->all();

        foreach ($products as $product) {
            $rowCount++;
            $productTranslation = $product->translation;
            $productCategory = $product->category;
            $productCategoryTranslation = $productCategory->translation;

            $productUserPrice = $product->getPriceByUserGroup(UserGroup::USER_GROUP_ALL_USERS);
            $productPartnerPrice = $product->getPrice();

            $productUserPrice = !empty($productUserPrice) ? $productUserPrice->getDiscountPrice() : 0;
            $productPartnerPrice = !empty($productPartnerPrice) ? $productPartnerPrice->getDiscountPrice() : 0;

            $this->writeProduct($activeSheet, $rowCount,
                'pg-pool.com',
                $product->sku,
                $productTranslation->title,
                $productCategoryTranslation->title,
                $product->productAvailability->translation->title,
                $productUserPrice,
                $productPartnerPrice
            );

            $productCombinations = $product->combinations;
            if(!empty($productCombinations)) {
                foreach ($productCombinations as $combination) {
                    $rowCount++;

                    $combinationTitle = $productTranslation->title;

                    foreach ($combination->combinationAttributes as $combinationAttribute) {
                        $combinationTitle .= ", " . $combinationAttribute->productAttribute->translation->title
                            . ": " . empty($combinationAttribute->productAttributeValue->translation->colorTexture)
                                ? $combinationAttribute->productAttributeValue->translation->value
                                : $combinationAttribute->productAttributeValue->translation->colorTexture->title;
                    }

                    $combinationUserPrice = $combination->getPriceByUserGroup(UserGroup::USER_GROUP_ALL_USERS);
                    $combinationPartnerPrice = $combination->getPrice();

                    $combinationUserPrice = !empty($combinationUserPrice) ? $combinationUserPrice->getDiscountPrice() : 0;
                    $combinationPartnerPrice = !empty($combinationPartnerPrice) ? $combinationPartnerPrice->getDiscountPrice() : 0;

                    $this->writeProduct($activeSheet, $rowCount,
                        'pg-pool.com',
                        $combination->sku,
                        $combinationTitle,
                        $productCategoryTranslation->title,
                        $combination->combinationAvailability->translation->title,
                        $combinationUserPrice,
                        $combinationPartnerPrice
                    );
                }
            }
        }

        $activeSheet->getStyle('A1:I1')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $activeSheet->getStyle('B2:B' . $rowCount)
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $activeSheet->getStyle('H1:H2')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $activeSheet->getStyle('I1:I2')
            ->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");;
        header("Content-Disposition: attachment;filename=pg-pool_products.xls");
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
//        return $this->render('index');
    }

    private function writeProduct($activeSheet, $row, $provider, $sku, $productTitle, $productCategoryTitle, $availability, $userPrice, $partnerPrice) {
        $activeSheet->SetCellValue('A' . $row, $provider);
        $activeSheet->SetCellValue('B' . $row, $sku);
        $activeSheet->SetCellValue('C' . $row, $productTitle);
        $activeSheet->SetCellValue('D' . $row, $productCategoryTitle);
        $activeSheet->SetCellValue('E' . $row, $availability);
        $activeSheet->SetCellValue('F' . $row, $userPrice);
        $activeSheet->SetCellValue('G' . $row, $partnerPrice);
    }
}
