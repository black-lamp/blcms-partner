<?php

use yii\db\Migration;

class m170227_160347_alter_partner_company_table extends Migration
{
    /**
     * @var string name of this table
     */
    public $tableName = 'partner_company';
    /**
     * @var string name of user table
     */
    public $userTableName = 'user';
    /**
     * @var string filed name with primary key
     */
    public $userPkField = 'id';
    /**
     * @var string name of city table
     */
    public $cityTableName = 'city';
    /**
     * @var string filed name with primary key
     */
    public $cityPkField = 'id';

    public function up()
    {
        $this->dropForeignKey($this->tableName . '-' . $this->userTableName . '-FK', $this->tableName);

        $this->addForeignKey(
            $this->tableName . '-' . $this->userTableName . '-FK',
            $this->tableName, 'ownerId',
            $this->userTableName, $this->userPkField,
            'cascade', 'cascade'
        );

    }

    public function down()
    {
        echo "m170227_160347_alter_partner_company_table cannot be reverted.\n";

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
