<?php
use yii\db\Migration;

/**
 * Insert categories to `partner_company_info_category` table
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170117_141029_add_categories_to_partner_company_info_category_table extends Migration
{
    public $tableName = '{{%partner_company_info_category}}';


    public function up()
    {
        $this->batchInsert($this->tableName, ['id', 'key'], [
            [ 1, 'emails' ],
            [ 2, 'phones' ]
        ]);
    }

    public function down()
    {
        $this->delete($this->tableName, 'id = 1');
        $this->delete($this->tableName, 'id = 2');

        return true;
    }
}
