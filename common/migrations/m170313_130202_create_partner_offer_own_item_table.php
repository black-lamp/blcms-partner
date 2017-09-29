<?php

use yii\db\Migration;

/**
 * Handles the creation of table `partner_offer_own_item`.
 */
class m170313_130202_create_partner_offer_own_item_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('partner_offer_own_item', [
            'id' => $this->primaryKey(),
            'offer_id' => $this->integer(),
            'title' => $this->string(),
            'description' => $this->text(),
            'image' => $this->string(),
            'price' => $this->float(),
            'number' => $this->integer()
        ]);

        $this->addForeignKey('partner_offer_own_item_offer_id_fk',
            'partner_offer_own_item', 'offer_id', 'partner_offer', 'id', 'cascade', 'cascade');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('partner_offer_own_item');
    }
}
