<?php

use yii\db\Migration;

class m170316_093254_add_partner_permissions_and_roles extends Migration
{
    public function up()
    {
        /** @var \yii\rbac\ManagerInterface $authManager */
        $authManager = Yii::$app->get('authManager');

        /**Adds roles**/
        $partnerAdministrator = $authManager->createRole('partnerAdministrator');
        $partnerAdministrator->description = 'Partner administrator';
        $authManager->add($partnerAdministrator);

        $partnerCompanyManager = $authManager->createRole('partnerCompanyManager');
        $partnerCompanyManager->description = 'Partner company manager';
        $authManager->add($partnerCompanyManager);

        $partnerOfferManager = $authManager->createRole('partnerOfferManager');
        $partnerOfferManager->description = 'Partner offer manager';
        $authManager->add($partnerOfferManager);

        $partnerMaterialManager = $authManager->createRole('partnerMaterialManager');
        $partnerMaterialManager->description = 'Partner material manager';
        $authManager->add($partnerMaterialManager);

        $partnerEmployeeModerator = $authManager->createRole('partnerEmployeeModerator');
        $partnerEmployeeModerator->description = 'Partner employee moderator';
        $authManager->add($partnerEmployeeModerator);


        $authManager->addChild($partnerAdministrator, $partnerCompanyManager);
        $authManager->addChild($partnerAdministrator, $partnerOfferManager);
        $authManager->addChild($partnerAdministrator, $partnerMaterialManager);
        $authManager->addChild($partnerAdministrator, $partnerEmployeeModerator);


        /**Adds permissions*/
        //Partner company manager
        $viewPartnerCompanies = $authManager->createPermission('viewPartnerCompanies');
        $viewPartnerCompanies->description = 'View partner companies';
        $authManager->add($viewPartnerCompanies);
        $authManager->addChild($partnerCompanyManager, $viewPartnerCompanies);

        $blockPartnerCompany = $authManager->createPermission('blockPartnerCompany');
        $blockPartnerCompany->description = 'Block partner companies';
        $authManager->add($blockPartnerCompany);
        $authManager->addChild($partnerCompanyManager, $blockPartnerCompany);

        $editPartnerCompany = $authManager->createPermission('editPartnerCompany');
        $editPartnerCompany->description = 'Edit partner companies';
        $authManager->add($editPartnerCompany);
        $authManager->addChild($partnerCompanyManager, $editPartnerCompany);

        //Partner offer manager
        $viewPartnerOffers = $authManager->createPermission('viewPartnerOffers');
        $viewPartnerOffers->description = 'View partner offers';
        $authManager->add($viewPartnerOffers);
        $authManager->addChild($partnerOfferManager, $viewPartnerOffers);

        $setPartnerOfferStatus = $authManager->createPermission('setPartnerOfferStatus');
        $setPartnerOfferStatus->description = 'Set partner offer status';
        $authManager->add($setPartnerOfferStatus);
        $authManager->addChild($partnerOfferManager, $setPartnerOfferStatus);

        //Partner material manager
        $managePartnerMaterials = $authManager->createPermission('managePartnerMaterials');
        $managePartnerMaterials->description = 'Manage partner materials';
        $authManager->add($managePartnerMaterials);
        $authManager->addChild($partnerMaterialManager, $managePartnerMaterials);

        //Partner employee moderator
        $moderatePartnerEmployees = $authManager->createPermission('moderatePartnerEmployees');
        $moderatePartnerEmployees->description = 'Moderate partner employees';
        $authManager->add($moderatePartnerEmployees);
        $authManager->addChild($partnerEmployeeModerator, $moderatePartnerEmployees);

    }

    public function down()
    {
        return false;
    }
}
