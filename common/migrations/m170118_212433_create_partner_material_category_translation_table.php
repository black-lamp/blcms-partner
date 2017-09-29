<?php
use yii\db\Migration;

/**
* Handles the creation of table `partner_material_category_translation`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
*/
class m170118_212433_create_partner_material_category_translation_table extends Migration
{
    public $tableName = '{{%partner_material_category_translation}}';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'categoryId' => $this->integer()->notNull(),
            'languageId' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);

        $this->addForeignKey(
            'material_category_translation-material_category-FK',
            $this->tableName, 'categoryId',
            '{{%partner_material_category}}', 'id',
            'CASCADE', 'CASCADE'
        );
        $this->addForeignKey(
            'material_category_translation-language-FK',
            $this->tableName, 'languageId',
            '{{%language}}', 'id'
        );

        $this->createIndex(
            'partner_material_category_translation-categoryId-IDX',
            $this->tableName, 'categoryId'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('material_category_translation-material_category-FK', $this->tableName);
        $this->dropForeignKey('material_category_translation-language-FK', $this->tableName);

        $this->dropIndex(
            'partner_material_category_translation-categoryId-IDX',
            $this->tableName
        );

        $this->dropTable($this->tableName);
    }
}

