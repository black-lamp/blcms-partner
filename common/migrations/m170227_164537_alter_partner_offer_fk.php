<?php

use yii\db\Migration;

class m170227_164537_alter_partner_offer_fk extends Migration
{
    /**
     * @var string Name of this table
     */
    public $tableName = '{{%partner_offer}}';

    public function up()
    {
        $this->dropForeignKey('partner_offer-partner_company_employee-FK', $this->tableName);

        $this->addForeignKey(
            'partner_offer-partner_company_employee-FK',
            $this->tableName, 'employeeId',
            'partner_company_employee', 'id',
            'cascade', 'cascade'
        );
    }

    public function down()
    {
        echo "m170227_164537_alter_partner_offer_fk cannot be reverted.\n";

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
