<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_company_info_category`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170116_133115_create_partner_company_info_category_table extends Migration
{
    const KEY_FIELD_MAX_LENGTH = 255;


    /**
     * @var string name of this table
     */
    public $tableName = 'partner_company_info_category';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'  => $this->primaryKey(),
            'key' => $this->string(self::KEY_FIELD_MAX_LENGTH)->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
