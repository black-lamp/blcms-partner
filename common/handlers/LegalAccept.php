<?php
namespace bl\cms\partner\common\handlers;

use Yii;

use bl\cms\partner\common\entities\EmployeeRequest;
use bl\cms\partner\common\entities\ModerationStatus;
use bl\cms\partner\common\entities\UserRequest;
use bl\cms\partner\common\components\PartnerMailer;

use bl\legalAgreement\common\events\LegalAccept as Event;

/**
 * Legal accept event handler
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class LegalAccept
{
    /**
     * Handler for LegalAccept event
     *
     * @param Event $event
     * @return boolean returns `true` if event was handler successfully
     */
    public static function handler(Event $event)
    {
        $userRequest = UserRequest::findOne(['userId' => $event->userId]);
        if ($userRequest && $userRequest->statusCode() == ModerationStatus::STATUS_ON_CONFIRMATION) {
            return self::userRequestHandler($userRequest);
        }

        $employeeRequest = EmployeeRequest::findOne(['userId' => $event->userId]);
        if ($employeeRequest && $employeeRequest->statusCode() == ModerationStatus::STATUS_ON_CONFIRMATION) {
            return self::employeeRequestHandler($employeeRequest);
        }

        return false;
    }

    /**
     * UserRequest handler
     *
     * @param UserRequest $request
     * @return boolean
     */
    private static function userRequestHandler($request)
    {
        $request->setStatusOnModeration();

        return true;
    }

    /**
     * EmployeeRequest handler
     *
     * @param EmployeeRequest $request
     * @return boolean
     */
    private static function employeeRequestHandler($request)
    {
        $request->setStatusAccepted();

        /** @var PartnerMailer $mailer */
        $mailer = Yii::$container->get(PartnerMailer::class);
        $mailer->sendEmployeeConfirmedNotification($request->userId);

        return Yii::$app->get('partnerCompanyManager')->hireEmployeeFromRequest($request);
    }
}
