<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

use bl\cms\partner\backend\Module as PartnerModule;

/**
 * View file for company controller in backend module
 *
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('company.list', 'List of companies');
?>

<div class="ibox">
    <div class="ibox-content">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'ownerId',
                    'label' => 'Owner',
                    'format' => 'html',
                    'value' => function ($data) {
                        return Html::a(
                                $data->owner->email,
                                Url::toRoute(['/user/admin/update', 'id' => $data['ownerId']]),
                                ['target' => '_blank']
                            );
                    }
                ],
                'name',
                'officialName',
                [
                    'label' => 'Employees count',
                    'value' => function ($data) {
                        return count($data->employees);
                    }
                ],
                [
                    'attribute' => 'siteLink',
                    'format' => 'html',
                    'value' => function ($data) {
                        return Html::a($data['siteLink'], $data['siteLink']);
                    }
                ],
                'createdAt:date',
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'header' => PartnerModule::t('company.list', 'Actions'),
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url) {
                            return Html::a(
                                    PartnerModule::t('company.list', 'View'),
                                    $url,
                                    [
                                        'class' => 'btn btn-sm btn-primary'
                                    ]
                                );
                        }
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>
