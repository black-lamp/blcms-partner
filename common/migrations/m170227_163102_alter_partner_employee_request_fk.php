<?php

use yii\db\Migration;

class m170227_163102_alter_partner_employee_request_fk extends Migration
{
    /**
     * @var string name of this table
     */
    public $tableName = '{{%partner_employee_request}}';


    public function up()
    {
        $this->dropForeignKey('partner_employee_request-partner_company-FK', $this->tableName);
        $this->dropForeignKey('partner_employee_request-user-FK', $this->tableName);

        $this->addForeignKey(
            'partner_employee_request-partner_company-FK',
            $this->tableName, 'companyId',
            '{{%partner_company}}', 'id',
            'cascade', 'cascade'
        );

        $this->addForeignKey(
            'partner_employee_request-user-FK',
            $this->tableName, 'userId',
            '{{%user}}', 'id',
            'cascade', 'cascade'
        );
    }

    public function down()
    {
        echo "m170227_163102_alter_partner_employee_request_fk cannot be reverted.\n";

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
