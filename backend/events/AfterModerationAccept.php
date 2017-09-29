<?php
namespace bl\cms\partner\backend\events;

use yii\base\Event;

/**
 * After moderation accept event
 *
 * @property $partnerId
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class AfterModerationAccept extends Event
{
    const EVENT_AFTER_MODERATION_ACCEPT = 'afterModerationAccept';


    /**
     * @var integer ID of user
     */
    public $partnerId;
}