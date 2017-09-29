<?php
use yii\helpers\Html;

/**
 * @var integer $count
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

if ($count > 0) {
    echo Html::tag('span',$count, [
        'class' => 'label label-info pull-right'
    ]);
}
