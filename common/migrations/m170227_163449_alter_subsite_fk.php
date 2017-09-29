<?php

use yii\db\Migration;

class m170227_163449_alter_subsite_fk extends Migration
{
    /**
     * @var string
     */
    public $tableName = '{{%partner_subsite_request}}';

    public function up()
    {
        $this->dropForeignKey('partner_subsite_request-partner_company-FK', $this->tableName);
        $this->addForeignKey(
            'partner_subsite_request-partner_company-FK',
            $this->tableName, 'companyId',
            'partner_company', 'id',
            'cascade', 'cascade'
        );

        $this->dropForeignKey('partner_company_info-partner_company-FK', 'partner_company_info');
        $this->addForeignKey(
            'partner_company_info-partner_company-FK',
            'partner_company_info', 'companyId',
            'partner_company', 'id',
            'cascade', 'cascade'
        );
    }

    public function down()
    {
        echo "m170227_163449_alter_subsite_fk cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
