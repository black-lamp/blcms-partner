<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_company_employee`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170116_132328_create_partner_company_employee_table extends Migration
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


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'        => $this->primaryKey(),
            'companyId' => $this->integer()->notNull(),
            'userId'    => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            $this->tableName . '-' . $this->companyTableName . '-FK',
            $this->tableName, 'companyId',
            $this->companyTableName, $this->companyPkField
        );

        $this->addForeignKey(
            $this->tableName . '-' . $this->companyTableName . '-FK2',
            $this->tableName, 'userId',
            $this->userTableName, $this->userPkField
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey($this->tableName . '-' . $this->companyTableName . '-FK', $this->tableName);
        $this->dropForeignKey($this->tableName . '-' . $this->userTableName . '-FK2', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
