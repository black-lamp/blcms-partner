<?php
use yii\db\Migration;

/**
 * Handles adding new columns to table `partner_user_request`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170118_103514_add_new_columns_to_partner_user_request_table extends Migration
{
    public $tableName = 'partner_user_request';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn($this->tableName, 'officialCompanyName', $this->string(500));
        $this->addColumn($this->tableName, 'site', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn($this->tableName, 'officialCompanyName');
        $this->dropColumn($this->tableName, 'site');
    }
}
