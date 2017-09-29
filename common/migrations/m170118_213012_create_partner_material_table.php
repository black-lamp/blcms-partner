<?php
use yii\db\Migration;

/**
* Handles the creation of table `partner_material`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
*/
class m170118_213012_create_partner_material_table extends Migration
{
    public $tableName = '{{%partner_material}}';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'categoryId' => $this->integer()->notNull(),
            'fileName' => $this->string(500)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'partner_material-partner_material_category-FK',
            $this->tableName, 'categoryId',
            '{{%partner_material_category}}', 'id',
            'CASCADE', 'CASCADE'
        );

        $this->createIndex(
            'partner_material-id-IDX',
            $this->tableName, 'id'
        );
        $this->createIndex(
            'partner_material-categoryId-IDX',
            $this->tableName, 'categoryId'
        );

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('partner_material-partner_material_category-FK', $this->tableName);

        $this->dropIndex(
            'partner_material-id-IDX',
            $this->tableName
        );
        $this->dropIndex(
            'partner_material-categoryId-IDX',
            $this->tableName
        );

        $this->dropTable($this->tableName);
    }
}

