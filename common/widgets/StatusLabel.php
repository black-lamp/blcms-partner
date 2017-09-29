<?php
namespace bl\cms\partner\common\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Widget for getting label by status
 *
 * @property array $statuses
 * @property string $defaultLabel
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class StatusLabel extends Widget
{
    /**
     * @var array configuration for statuses and labele
     * Configuration example
     * ```php
     * [
     *      OfferStatus::STATUS_UNDEFINED => 'default',
     *      OfferStatus::STATUS_ON_RESERVATION => 'warning',
     *      OfferStatus::STATUS_ORDERING => 'warning',
     *      OfferStatus::STATUS_SENT_TO_CLIENT => 'success'
     *      // ...
     * ]
     * ```
     */
    public $statuses = [];
    /**
     * @var string Default label
     */
    public $defaultLabel = 'default';


    /**
     * Get label by status
     *
     * @param mixed $status
     * @param string $text
     * @return string
     */
    public function getLabel($status, $text = '')
    {
        $class = (ArrayHelper::keyExists($status, $this->statuses)) ?
            $this->statuses[$status]:
            $this->defaultLabel;

        return Html::tag('span', $text, [
            'class' => 'label label-' . $class
        ]);
    }
}
