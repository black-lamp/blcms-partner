<?php

use yii\db\Migration;

class m170309_135043_add_table_partner_offer_item_additional_product extends Migration
{
    public function up()
    {
        $this->createTable('partner_offer_item_additional_product', [
            'id' => $this->primaryKey(),
            'partner_offer_item_id' => $this->integer(),
            'additional_product_id' => $this->integer(),
            'number' => $this->integer()
        ]);

        $this->addForeignKey('partner_offer_item_additional_product:partner_offer_item_id',
            'partner_offer_item_additional_product', 'partner_offer_item_id', 'partner_offer_item', 'id', 'cascade', 'cascade');

        $this->addForeignKey('partner_offer_item_additional_product:shop_product_id',
            'partner_offer_item_additional_product', 'additional_product_id', 'shop_product', 'id', 'cascade', 'cascade');

    }

    public function down()
    {
        $this->dropTable('partner_offer_item_additional_product');
    }

}
