<?php

use yii\db\Migration;

class m170227_161910_alter_partner_company_employee_table extends Migration
{
    /**
     * @var string name of this table
     */
    public $tableName = 'partner_company_employee';
    /**
     * @var string name of company table
     */
    public $companyTableName = 'partner_company';
    /**
     * @var string filed name with primary key
     */
    public $companyPkField = 'id';
    /**
     * @var string name of user table
     */
    public $userTableName = 'user';
    /**
     * @var string filed name with primary key
     */
    public $userPkField = 'id';

    public function up()
    {
        $this->dropForeignKey($this->tableName . '-' . $this->companyTableName . '-FK', $this->tableName);
        $this->dropForeignKey($this->tableName . '-' . $this->companyTableName . '-FK2', $this->tableName);

        $this->addForeignKey(
            $this->tableName . '-' . $this->companyTableName . '-FK',
            $this->tableName, 'companyId',
            $this->companyTableName, $this->companyPkField,
            'cascade', 'cascade'
        );
        $this->addForeignKey(
            $this->tableName . '-' . $this->companyTableName . '-FK2',
            $this->tableName, 'userId',
            $this->userTableName, $this->userPkField,
            'cascade', 'cascade'
        );
    }

    public function down()
    {
        echo "m170227_161910_alter_partner_company_employee_table cannot be reverted.\n";

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
