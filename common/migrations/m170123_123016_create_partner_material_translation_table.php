<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_material_translation`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170123_123016_create_partner_material_translation_table extends Migration
{
    const TITLE_FIELD_MAX_LENGTH = 255;


    /**
     * @var string name of this table
     */
    public $tableName = '{{%partner_material_translation}}';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'materialId' => $this->integer()->notNull(),
            'languageId' => $this->integer()->notNull(),
            'title'      => $this->string(self::TITLE_FIELD_MAX_LENGTH)->notNull()
        ]);

        $this->addForeignKey(
            'partner_material_translation-partner_material-FK',
            $this->tableName, 'materialId',
            'partner_material', 'id'
        );
        $this->addForeignKey(
            'partner_material_translation-language-FK',
            $this->tableName, 'languageId',
            'language', 'id'
        );

        $this->createIndex(
            'partner_material_translation-materialId-IDX',
            $this->tableName, 'materialId'
        );
        $this->createIndex(
            'partner_material_translation-languageId-IDX',
            $this->tableName, 'languageId'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('partner_material_translation-partner_material-FK', $this->tableName);
        $this->dropForeignKey( 'partner_material_translation-language-FK', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
