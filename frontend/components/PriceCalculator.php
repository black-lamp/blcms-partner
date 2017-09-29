<?php
namespace bl\cms\partner\frontend\components;

use bl\cms\partner\common\entities\CommercialOffer;
use yii\base\Object;

use bl\cms\shop\common\entities\Product;

/**
 * Price calculator
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class PriceCalculator extends Object
{
    /**
     * Get sum of products by user group
     *
     * @param  CommercialOffer $offer
     * @param Product[] $products
     * @param integer $userGroup
     * @return float
     */
    public function getProductsSumByUserGroup($offer, $products, $userGroup)
    {
        $sum = $offer->getProductsSum($userGroup);

        //Gets own items price
        if (!empty($offer)) {

            if (!empty($ownItems = $offer->ownItems)) {
                foreach ($ownItems as $ownItem) {
                    $sum += $ownItem->price * $ownItem->number;
                }
            }
        }

        return $sum;
    }

    /**
     * Calc walrus
     *
     * @param float $defaultSum
     * @param float $wholesaleSum
     * @return float
     */
    public function calcWalrusInPercent(float $defaultSum, float $wholesaleSum):float
    {
        if($wholesaleSum == 0 || $defaultSum == 0) {
            return 0;
        }
        $walrus = ($defaultSum - $wholesaleSum) / $defaultSum * 100;

        return $walrus;
    }

    /**
     * Calc walrus
     *
     * @param float $defaultSum
     * @param float $wholesaleSum
     * @return float
     */
    public function calcWalrus($defaultSum, $wholesaleSum)
    {
        $walrus = $defaultSum - $wholesaleSum;

        return $walrus;
    }
}