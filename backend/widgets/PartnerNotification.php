<?php
namespace bl\cms\partner\backend\widgets;

use yii\base\Widget;

use bl\cms\partner\common\entities\OfferStatus;
use bl\cms\partner\common\entities\CommercialOffer;
use bl\cms\partner\common\entities\EmployeeRequest;
use bl\cms\partner\common\entities\ModerationStatus;
use bl\cms\partner\common\entities\UserRequest;

/**
 * Widget for counting a notifications from partner module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class PartnerNotification extends Widget
{
    /**
     * @var integer
     */
    private $_count = 0;


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_count += CommercialOffer::find()
            ->where(['statusId' => OfferStatus::STATUS_ON_RESERVATION])
            ->orWhere(['statusId' => OfferStatus::STATUS_ORDERING])
            ->count('id');

        $this->_count += UserRequest::find()
            ->where(['statusId' => ModerationStatus::STATUS_ON_MODERATION])
            ->count('id');

        $this->_count += EmployeeRequest::find()
            ->where(['statusId' => ModerationStatus::STATUS_ON_MODERATION])
            ->count('id');
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->_count;
    }
}