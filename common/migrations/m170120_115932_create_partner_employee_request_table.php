<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_employee_request`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170120_115932_create_partner_employee_request_table extends Migration
{
    const STATUS_ON_MODERATION = 2;


    /**
     * @var string name of this table
     */
    public $tableName = '{{%partner_employee_request}}';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'          => $this->primaryKey(),
            'companyId'   => $this->integer()->notNull(),
            'userId'      => $this->integer()->notNull(),
            'statusId'    => $this->integer()->notNull()->defaultValue(self::STATUS_ON_MODERATION),
            'createdAt'   => $this->integer()->notNull(),
            'moderatedAt' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            'partner_employee_request-partner_company-FK',
            $this->tableName, 'companyId',
            '{{%partner_company}}', 'id'
        );

        $this->addForeignKey(
            'partner_employee_request-user-FK',
            $this->tableName, 'userId',
            '{{%user}}', 'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('partner_employee_request-partner_company-FK', $this->tableName);
        $this->dropForeignKey('partner_employee_request-user-FK', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
