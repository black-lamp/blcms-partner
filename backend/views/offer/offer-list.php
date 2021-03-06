<?php
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\partner\backend\Module as PartnerModule;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\partner\common\entities\CommercialOffer[] $offers
 * @var \bl\cms\partner\common\entities\CompanyEmployee[] $employees
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('offer', 'Commercial offer list');
?>

<div class="ibox">
    <div class="ibox-content">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <?= PartnerModule::t('offer', 'Company') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Name') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Sum') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Date added') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Products') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Own products') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Employee') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Status') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Actions') ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($offers as $number => $offer): ?>
                    <tr>
                        <td>
                            <?= $number + 1 ?>
                        </td>
                        <td>
                            <?= Html::encode($employees[$offer->employee->id]->company->name) ?>
                        </td>
                        <td>
                            <?= Html::a(
                                $offer->title,
                                Url::toRoute(['product-list', 'offerId' => $offer->id])
                            ) ?>
                        </td>
                        <td>
                            <?= \Yii::$app->formatter->asCurrency($offer->employeeProductsSum) ?>
                        </td>
                        <td>
                            <?= $offer->created_at ?>
                        </td>
                        <td>
                            <?= $offer->itemsCount ?>
                        </td>
                        <td>
                            <?= $offer->ownItemsCount ?>
                        </td>
                        <td>
                            <?= Html::a(
                                $offer->employee->user->email,
                                Url::toRoute(['/user/admin/update', 'id' => $offer->employee->userId]),
                                ['target' => '_blank']
                            ) ?>
                        </td>
                        <td>
                            <span class="label label-primary" style="background-color: <?= $offer->status->color ?>">
                                <?= $offer->status->translation->title ?>
                            </span>
                        </td>
                        <td>
                            <?= Html::a(
                                PartnerModule::t('offer', 'View'),
                                Url::toRoute(['view', 'id' => $offer->id]),
                                ['class' => 'btn btn-xs btn-primary']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
