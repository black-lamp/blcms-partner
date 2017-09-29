<?php
use yii\db\Migration;

/**
* Handles the creation of table `partner_material_category`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
*/
class m170118_212000_create_partner_material_category_table extends Migration
{
    public $tableName = '{{%partner_material_category}}';

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
        ]);

        $this->createIndex(
            'partner_material_category-id-IDX',
            $this->tableName,
            'id'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropIndex('partner_material_category-id-IDX', $this->tableName);

        $this->dropTable($this->tableName);
    }
}

