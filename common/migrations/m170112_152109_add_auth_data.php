<?php
use yii\db\Migration;

/**
 * Add permission and roles for module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170112_152109_add_auth_data extends Migration
{
    public function up()
    {
        /** @var \yii\rbac\ManagerInterface $authManager */
        $authManager = Yii::$app->get('authManager');

        // employee's permissions
        $seeCompanyInfo = $authManager->createPermission('seeCompanyInfo');
        $seeCompanyInfo->description = 'Read information about company';
        $authManager->add($seeCompanyInfo);

        $marketingManage = $authManager->createPermission('marketingManage');
        $marketingManage->description = 'Manage of materials for marketing';
        $authManager->add($marketingManage);

        $seeCommercialOffers = $authManager->createPermission('seeCommercialOffers');
        $seeCommercialOffers->description = 'See commercial offers';
        $authManager->add($seeCommercialOffers);

        $guaranteeRegistration = $authManager->createPermission('guaranteeRegistration');
        $guaranteeRegistration->description = 'Registration of guarantee';
        $authManager->add($guaranteeRegistration);

        // director's permissions
        $seeEmployeesCommercialOffers = $authManager->createPermission('seeEmployeesCommercialOffers');
        $seeEmployeesCommercialOffers->description = 'See employees commercial offers';
        $authManager->add($seeEmployeesCommercialOffers);

        $editCompanyInfo = $authManager->createPermission('editCompanyInfo');
        $editCompanyInfo->description = 'Edit company information';
        $authManager->add($editCompanyInfo);

        $employeesManage = $authManager->createPermission('employeesManage');
        $employeesManage->description = 'Manage of employees in the company';
        $authManager->add($employeesManage);

        $siteManage = $authManager->createPermission('siteManage');
        $siteManage->description = 'Manage of company site';
        $authManager->add($siteManage);

        // roles
        $employee = $authManager->createRole('employee');
        $employee->description = 'Employee of the company';
        $authManager->add($employee);

        $director = $authManager->createRole('director');
        $director->description = 'Director of the company';
        $authManager->add($director);

        $authManager->addChild($employee, $seeCompanyInfo);
        $authManager->addChild($employee, $marketingManage);
        $authManager->addChild($employee, $seeCommercialOffers);
        $authManager->addChild($employee, $guaranteeRegistration);

        $authManager->addChild($director, $employee);
        $authManager->addChild($director, $seeEmployeesCommercialOffers);
        $authManager->addChild($director, $editCompanyInfo);
        $authManager->addChild($director, $employeesManage);
        $authManager->addChild($director, $siteManage);
    }

    public function down()
    {
        /** @var \yii\rbac\ManagerInterface $authManager */
        $authManager = Yii::$app->get('authManager');

        $director = $authManager->getRole('director');
        $employee = $authManager->getRole('employee');

        $authManager->removeChildren($director);
        $authManager->removeChildren($employee);

        $authManager->remove($director);
        $authManager->remove($employee);

        $seeCompanyInfo = $authManager->getPermission('seeCompanyInfo');
        $authManager->remove($seeCompanyInfo);

        $marketingManage = $authManager->getPermission('marketingManage');
        $authManager->remove($marketingManage);

        $seeCommercialOffers = $authManager->getPermission('seeCommercialOffers');
        $authManager->remove($seeCommercialOffers);

        $guaranteeRegistration = $authManager->getPermission('guaranteeRegistration');
        $authManager->remove($guaranteeRegistration);

        $seeEmployeesCommercialOffers = $authManager->getPermission('seeEmployeesCommercialOffers');
        $authManager->remove($seeEmployeesCommercialOffers);

        $editCompanyInfo = $authManager->getPermission('editCompanyInfo');
        $authManager->remove($editCompanyInfo);

        $employeesManage = $authManager->getPermission('employeesManage');
        $authManager->remove($employeesManage);

        $siteManage = $authManager->getPermission('siteManage');
        $authManager->remove($siteManage);


        return true;
    }
}
