<?php
namespace bl\cms\partner\backend\components;

use bl\cms\partner\backend\controllers\ModerationController;
use bl\cms\partner\backend\events\AfterModerationAccept;
use bl\cms\shop\common\components\user\models\User;
use yii\base\BootstrapInterface;
use yii\base\Event;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class PartnersBootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        Event::on(ModerationController::className(), AfterModerationAccept::EVENT_AFTER_MODERATION_ACCEPT, [$this, 'apply']);
    }

    public function apply($event) {

        $user = User::findOne($event->partnerId);
        $user->user_group_id = 2;
        $user->save();
    }

}