<?php
use yii\db\Migration;

/**
 * Handles adding `statusId` column to table `partner_company`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170208_121155_add_status_column_to_partner_company_table extends Migration
{
    const STATUS_ACTIVE = 1;


    /**
     * @var string Table name
     */
    public $tableName = 'partner_company';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn(
            $this->tableName,
            'statusId',
            $this->integer()->notNull()->defaultValue(self::STATUS_ACTIVE)
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->tableName, 'statusId');
    }
}
