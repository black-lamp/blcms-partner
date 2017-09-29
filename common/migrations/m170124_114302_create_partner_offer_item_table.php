<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_offer_item`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170124_114302_create_partner_offer_item_table extends Migration
{
    /**
     * @var string Name of this table
     */
    public $tableName = '{{%partner_offer_item}}';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'            => $this->primaryKey(),
            'offerId'       => $this->integer()->notNull(),
            'productId'     => $this->integer()->notNull(),
            'combinationId' => $this->integer()->null(),
            'quantity'      => $this->integer()->notNull()->defaultValue(1),
            'position'      => $this->integer()
        ]);

        $this->addForeignKey(
            'partner_offer_item-partner_offer-FK',
            $this->tableName, 'offerId',
            'partner_offer', 'id',
            'CASCADE', 'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey('partner_offer_item-partner_offer-FK', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
