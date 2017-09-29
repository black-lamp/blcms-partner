<?php
use bl\cms\partner\backend\Module as PartnerModule;

/**
 * @var \yii\web\View $this
 * @var \bl\cms\shop\common\entities\Product[] $products
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('offer', 'Products list');
?>

<div class="ibox">
    <div class="ibox-content">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>
                        <?= PartnerModule::t('offer', 'Name') ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $number => $product): ?>
                    <tr>
                        <td>
                            <?= $number + 1 ?>
                        </td>
                        <td>
                            <?= $product->translation->title ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
