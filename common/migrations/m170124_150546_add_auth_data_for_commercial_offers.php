<?php
use yii\db\Migration;

/**
 * Add permission for commercial offers
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170124_150546_add_auth_data_for_commercial_offers extends Migration
{
    public function up()
    {
        /** @var \yii\rbac\ManagerInterface $authManager */
        $authManager = Yii::$app->get('authManager');

        $createCommercialOffer = $authManager->createPermission('createCommercialOffer');
        $createCommercialOffer->description = 'Create commercial offers';
        $authManager->add($createCommercialOffer);

        $employeeRole = $authManager->getRole('employee');

        $authManager->addChild($employeeRole, $createCommercialOffer);
    }

    public function down()
    {
        /** @var \yii\rbac\ManagerInterface $authManager */
        $authManager = Yii::$app->get('authManager');

        $createCommercialOffer = $authManager->getPermission('createCommercialOffer');
        $employeeRole = $authManager->getRole('employee');

        $authManager->removeChild($employeeRole, $createCommercialOffer);
        $authManager->remove($createCommercialOffer);

        return true;
    }
}
