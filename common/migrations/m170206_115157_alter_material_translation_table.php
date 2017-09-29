<?php
use yii\db\Migration;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170206_115157_alter_material_translation_table extends Migration
{
    /**
     * @var string name of this table
     */
    public $tableName = '{{%partner_material_translation}}';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'partner_material_translation-partner_material-FK', $this->tableName);
        $this->addForeignKey(
            'partner_material_translation-partner_material-FK',
            $this->tableName, 'materialId',
            'partner_material', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'partner_material_translation-partner_material-FK', $this->tableName);

        $this->addForeignKey(
            'partner_material_translation-partner_material-FK',
            $this->tableName, 'materialId',
            'partner_material', 'id'
        );
    }
}
