<?php

use yii\db\Migration;

class m170313_100228_add_edit_commercial_employee_permission extends Migration
{
    public function up()
    {
        /** @var \yii\rbac\ManagerInterface $authManager */
        $authManager = Yii::$app->get('authManager');

        $editCommercialEmployee = $authManager->createPermission('editCommercialEmployee');
        $editCommercialEmployee->description = 'Edit commercial employee';
        $authManager->add($editCommercialEmployee);

        $employee = $authManager->getRole('employee');

        $authManager->addChild($employee, $editCommercialEmployee);

    }

    public function down()
    {
        /** @var \yii\rbac\ManagerInterface $authManager */
        $authManager = Yii::$app->get('authManager');

        $editCommercialEmployee = $authManager->getPermission('editCommercialEmployee');
        $employee = $authManager->getRole('employee');

        $authManager->removeChild($employee, $editCommercialEmployee);

        $authManager->remove($editCommercialEmployee);

    }

}
