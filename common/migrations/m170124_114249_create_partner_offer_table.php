<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_offer`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170124_114249_create_partner_offer_table extends Migration
{
    /**
     * @var string Name of this table
     */
    public $tableName = '{{%partner_offer}}';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'employeeId' => $this->integer()->notNull(),
            'statusId'   => $this->integer()->notNull()->defaultValue(1),
            'title'      => $this->string(500)->notNull()
        ]);

        $this->addForeignKey(
            'partner_offer-partner_company_employee-FK',
            $this->tableName, 'employeeId',
            'partner_company_employee', 'id'
        );

        $this->createIndex(
            'partner_offer-employeeId-IDX',
            $this->tableName, 'employeeId'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('partner_offer-partner_company_employee-FK', $this->tableName);

        $this->dropIndex('partner_offer-employeeId-IDX', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
