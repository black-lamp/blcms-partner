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

// TODO: remove view
$this->title = PartnerModule::t('offer', 'Request to products reservation');
?>

<div class="ibox">
    <div class="ibox-content">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <?= PartnerModule::t('offer', 'Company') ?>
                    </th>
                    <th>
                        <?= PartnerModule::t('offer', 'Employee') ?>
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
                                $offer->employee->user->email,
                                Url::toRoute(['/user/admin/update', 'id' => $offer->employee->userId]),
                                ['target' => '_blank']
                            ) ?>
                        </td>
                        <td>
                            <?= Html::a(
                                PartnerModule::t('offer', 'View'),
                                Url::toRoute(['product-list', 'offerId' => $offer->id]),
                                ['class' => 'btn btn-sm btn-primary']
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
