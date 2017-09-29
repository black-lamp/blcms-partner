<?php
use bl\cms\shop\common\components\user\models\UserGroup;
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\common\entities\CompanyInfoCategory;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\CommercialOffer $offer
 * @var \bl\cms\partner\common\entities\CompanyEmployee $employee
 * @var \bl\cms\partner\common\entities\PartnerCompany $company
 * @var \bl\cms\shop\common\entities\Product[] $products
 * @var string $logoRootUrl
 * @var float $discount
 * @var float $sum
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
?>

<table border="0" cellpadding="0" cellspacing="0" style="display: block; width: 100%; max-width: 600px; margin: 0 auto; padding: 0;">
    <tbody style="display: block; width: 100%;">
        <tr style="display: block; width: 100%;">
            <td style="float: left">
                <a href="<?= $company->siteLink ?>" target="_blank">
                    <?= Html::img($logoRootUrl . '/' . $company->logo, [
                        'alt' => '',
                        'width' => '100'
                    ]) ?>
                </a>
            </td>
            <td style="float: right">
                <b>
                    <p>
                        <?= Html::mailto($employee->user->email, $employee->user->email) ?>
                    </p>
                    <p>
                        <?= Html::a($employee->user->profile->phone, 'tel:' . $employee->user->profile->phone) ?>
                    </p>
                </b>
            </td>
            <td style="display: block; width: 100%; clear: both;"></td>
        </tr>
        <tr style="display: block; width: 100%;">
            <td style="display: block; width: 100%;">
                <h1 style="width: 100%; text-align: center; font-weight: normal">
                    <?= $offer->title ?>
                </h1>
            </td>
        </tr>
        <?php if (!empty($products)): ?>
        <tr style="display: block; width: 100%;">
            <td style="display: block; width: 100%;">
                <table border="0" cellpadding="0" cellspacing="0" style="display: block; width: 100%; margin: 0; padding: 0;">
                    <tbody style="display: block; width: 100%;">
                        <?php $currentCategory = null ?>
                        <?php foreach ($offer->items as $item): ?>
                            <?php $hasCombination = $products[$item->productId]->hasCombinations(); ?>
                            <tr style="display: block; width: 100%; margin-bottom: 20px;">
                                <td style="display: block; width: 100%">
                                    <?php if ($currentCategory != $products[$item->productId]->category_id): ?>
                                        <?php
                                        $currentCategory = $products[$item->productId]->category_id;
                                        echo $products[$item->productId]->category->translation->title;
                                        ?>
                                    <?php endif; ?>
                                </td>
                                <td style="display: inline-block; width: 100px">
                                    <?php
                                    $imageRoute = $products[$item->productId]->image->getSmall();
                                    if ($hasCombination) {
                                        $imageRoute = '';
                                        $images = $products[$item->productId]->getCombination($item->combinationId)
                                            ->images;
                                        if (isset($images[0])) {
                                            $imageRoute = $images[0]->productImage->getSmall();
                                        }
                                    }
                                    ?>
                                    <?= Html::img(Url::to($imageRoute, true), ['width' => 100]) ?>
                                </td>
                                <td style="display: inline-block; max-width: 300px">
                                    <h3>
                                        <?= Html::a(
                                            $products[$item->productId]->translation->title,
                                                Url::to(['/shop/product/show', 'id' => $products[$item->productId]->id], true),
                                                ['target' => '_blank']
                                        ) ?>
                                    </h3>
                                    <p>
                                        <?= $products[$item->productId]->translation->description ?>
                                    </p>
                                </td>
                                <td style="display: inline-block; max-width: 100px; padding: 0 10px;">
                                    <span style="font-size: 20px;">
                                        <?php $price = $products[$item->productId]
                                            ->getPriceByUserGroup(UserGroup::USER_GROUP_ALL_USERS)->discount ?>
                                        <?php if ($hasCombination): ?>
                                            <?php $price = $products[$item->productId]->getCombination($item->combinationId)
                                                ->getPriceByUserGroup(UserGroup::USER_GROUP_ALL_USERS)
                                                ->getDiscountPrice() ?>
                                        <?php endif; ?>
                                        <?= Yii::$app->formatter->asCurrency($price) ?>
                                    </span>
                                    <span>
                                        <?= PartnerModule::t('offer', 'Quantity: {value}', [
                                                'value' => $item->quantity
                                        ]) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr style="display: block; width: 100%;">
            <td style="display: block; width: 100%;" align="right">
                <p style="font-size: 16px;">
                    <?= PartnerModule::t('offer.letter', 'Old sum: {sum}', [
                        'sum' => Html::tag('del', Yii::$app->formatter->asCurrency($sum))
                    ]) ?>
                </p>
                <p style="font-size: 20px;">
                    <?= PartnerModule::t('offer.letter', 'Sum: {sum} ({discount}%)', [
                        'sum' => $sum * (100 - $discount) / 100,
                        'discount' => $discount
                    ]) ?>
                </p>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
