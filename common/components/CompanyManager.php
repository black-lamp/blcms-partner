<?php
namespace bl\cms\partner\common\components;

use bl\cms\partner\common\entities\EmployeeRequest;
use Exception;

use Yii;
use yii\web\User;
use yii\base\Object;
use yii\rbac\ManagerInterface;

use bl\cms\partner\common\entities\ModerationStatus;
use bl\cms\partner\common\entities\PartnerCompany;
use bl\cms\partner\common\entities\CompanyEmployee;

/**
 * Component for management of company
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class CompanyManager extends Object
{
    /**
     * Method for creation the company from [[UserRequest]]
     *
     * @param \bl\cms\partner\common\entities\UserRequest $request
     * @return integer|bool returns company ID if company was successfully created
     * @throws Exception
     */
    public function createCompany($request)
    {
        if ($request->statusId === ModerationStatus::STATUS_ACCEPTED) {
            $transaction = PartnerCompany::getDb()->beginTransaction();
            try {
                $company = new PartnerCompany([
                    'ownerId' => $request->userId,
                    'name' => $request->companyName,
                    'officialName' => $request->officialCompanyName,
                    'siteLink' => $request->site,
                    'city' => $request->city
                ]);
                $company->insert();

                $employee = new CompanyEmployee([
                    'companyId' => $company->id,
                    'userId' => $request->userId
                ]);
                $employee->insert();

                /** @var ManagerInterface $authManager */
                $authManager = Yii::$app->get('authManager');
                $directorRole = $authManager->getRole('director');

                $assignment = $authManager->getAssignment($directorRole->name, $company->ownerId);
                if (empty($assignment)) {
                    $authManager->assign($directorRole, $company->ownerId);
                }

                $transaction->commit();

                return $company->id;
            }
            catch (Exception $ex) {
                $transaction->rollBack();
                throw $ex;
            }
        }

        return false;
    }

    /**
     * Return current employee
     *
     * @param boolean $onlyId if set `true` - method will return a employee ID
     * @return null|integer|CompanyEmployee returns `null` if user guest of user not found
     */
    public function currentEmployee($onlyId = false)
    {
        /** @var User $user */
        $user = Yii::$app->get('user');

        if (!$user->isGuest && $employee = CompanyEmployee::findOne(['userId' => $user->id])) {
            return ($onlyId) ? $employee->id : $employee;
        }

        return null;
    }

    /**
     * Hire employee to the company
     *
     * @param integer $companyId
     * @param integer $userId
     * @return bool returns `true` if employee was successfully hired
     */
    public function hireEmployee($companyId, $userId)
    {
        $companyEmployee = new CompanyEmployee([
            'companyId' => $companyId,
            'userId' => $userId
        ]);

        if ($companyEmployee->insert()) {
            /** @var \yii\rbac\ManagerInterface $authManager */
            $authManager = Yii::$app->get('authManager');
            $employeeRole = $authManager->getRole('employee');
            $authManager->assign($employeeRole, $userId);

            return true;
        }

        return false;
    }

    /**
     * Hire employee to the company from employee request
     *
     * @param \bl\cms\partner\common\entities\EmployeeRequest $request
     * @return boolean returns `true` if employee was successfully hired
     */
    public function hireEmployeeFromRequest($request)
    {
        if ($request->statusCode() == ModerationStatus::STATUS_ACCEPTED) {
            return $this->hireEmployee($request->companyId, $request->userId);
        }

        return false;
    }

    /**
     * Fire employee from company
     *
     * @param integer $id Employee ide
     * @return boolean
     * @throws Exception
     */
    public function fireEmployee($id)
    {
        if ($employee = CompanyEmployee::findOne($id)) {
            $userId = $employee->userId;

            $transaction = CompanyEmployee::getDb()->beginTransaction();
            try {
                $employee->delete();

                EmployeeRequest::findOne(['userId' => $userId])
                    ->setStatusDecline();

                /** @var \yii\rbac\ManagerInterface $authManager */
                $authManager = Yii::$app->get('authManager');
                $employeeRole = $authManager->getRole('employee');
                $authManager->revoke($employeeRole, $userId);

                $transaction->commit();
            }
            catch (Exception $ex) {
                $transaction->rollBack();
                throw $ex;
            }
        }

        return false;
    }
}