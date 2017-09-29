<?php
namespace bl\cms\partner\frontend\widgets;

use yii\base\Widget;

/**
 * Widget for rendering messages with params
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class Message extends Widget
{
    /**
     * @var string message with keywords
     */
    public $message;
    /**
     * @var array params for message
     */
    public $params = [];


    /**
     * @inheritdoc
     */
    public function run()
    {
        if (empty($this->params)) {
            echo $this->message;
        }
        else {
            echo strtr($this->message, $this->params);
        }
    }
}
