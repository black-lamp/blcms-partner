<?php
namespace bl\cms\partner\backend\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;

use bl\cms\partner\common\entities\CommercialOffer;

/**
 * Widget for displaying count of product requests by status
 *
 * @property integer $status
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class ProductCount extends Widget
{
    /**
     * @var integer
     */
    public $status = null;


    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->status)) {
            throw new InvalidConfigException('You should set a $status property');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $count = CommercialOffer::find()
            ->where(['statusId' => $this->status])
            ->count('id');

        return $this->render('counter', [
            'count' => $count
        ]);
    }
}