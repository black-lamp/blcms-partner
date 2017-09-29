<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_subsite_request`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170212_191347_create_partner_subsite_request_table extends Migration
{
    /**
     * @var string
     */
    public $tableName = '{{%partner_subsite_request}}';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'companyId' => $this->integer()->notNull(),
            'domainName' => $this->string()->notNull(),
            'hasHosting' => $this->boolean()->null()
        ]);

        $this->addForeignKey(
            'partner_subsite_request-partner_company-FK',
            $this->tableName, 'companyId',
            'partner_company', 'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('partner_subsite_request-partner_company-FK', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
