<?php
namespace bl\cms\partner\common\components;

use Yii;
use yii\base\Object;

use bl\cms\partner\common\entities\UserRequest;
use bl\cms\partner\common\entities\EmployeeRequest;
use bl\cms\partner\common\entities\NotFoundRequest;
use bl\cms\partner\common\entities\CompanyEmployee;
use bl\cms\partner\common\entities\PartnerCompany;
use bl\cms\partner\common\entities\ModerationStatus;

/**
 * Wrapper for default User component
 *
 * @var \yii\web\User $component
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class User extends Object
{
    /**
     * @var \yii\web\User
     */
    public $component = null;


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->component = Yii::$app->get('user');
    }

    /**
     * Check is user a partner
     *
     * @param null|integer $userId
     * @return bool return `true` if user a partner
     */
    public function isUserPartner($userId = null)
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            $id = ($userId === null) ? $this->component->id : $userId;

            $isHasCompany = PartnerCompany::findOne(['ownerId' => $id]);
            $isUserEmployee = CompanyEmployee::findOne(['userId' => $id]);
            $isHasEmployeeRequest = EmployeeRequest::find()
                ->where(['userId' => $id])
                ->andWhere(['!=', 'statusId', ModerationStatus::STATUS_DECLINE])
                ->all();
            $isHasPartnerRequest = UserRequest::find()
                ->where(['userId' => $id])
                ->andWhere(['!=', 'statusId', ModerationStatus::STATUS_DECLINE])
                ->all();

            if ($isHasCompany || $isUserEmployee || $isHasEmployeeRequest || $isHasPartnerRequest) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isUserAcceptedPartner(int $userId):bool
    {
        $partnerRequest = UserRequest::find()
            ->where(['userId' => $userId])
            ->andWhere(['statusId' => ModerationStatus::STATUS_ACCEPTED])
            ->count();

        return boolval($partnerRequest);
    }

    /**
     * @param int $userId
     * @param int $companyId
     * @return bool
     */
    public function isUserCompanyEmployee(int $userId, int $companyId):bool
    {
        $companyEmployee = CompanyEmployee::find()->select(['id'])
            ->where(['companyId' => $companyId, 'userId' => $userId])->one();

        return boolval($companyEmployee);
    }

    /**
     * @param int $userId
     * @return bool
     */
    public function isUserEmployee(int $userId):bool
    {
        $companyEmployee = CompanyEmployee::find()->select(['id'])
            ->where(['userId' => $userId])->one();

        return boolval($companyEmployee);
    }

    /**
     * Getting company by user id
     *
     * @param null|integer $userId
     * @return NotFoundRequest|UserRequest|EmployeeRequest
     */
    public function getRequest($userId = null)
    {
        if (!Yii::$app->getUser()->getIsGuest()) {
            $id = ($userId === null) ? $this->component->id : $userId;

            if ($request = UserRequest::findOne(['userId' => $id])) {
                return $request;
            }
            elseif ($request = EmployeeRequest::findOne(['userId' => $id])) {
                return $request;
            }
        }

        return new NotFoundRequest();
    }

    /**
     * Get company id where current user is work
     *
     * @return int|null returns ID of the company or `null` if user
     * is not work in any company
     */
    public function getCompanyId()
    {
        $user = Yii::$app->getUser();
        if (!$user->getIsGuest() &&
            $emp = CompanyEmployee::findOne(['userId' => $user->getId()])) {
            return $emp->companyId;
        }

        return null;
    }
}
