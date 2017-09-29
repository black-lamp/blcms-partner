<?php

use yii\db\Migration;

class m170314_152803_alter_partner_offer_own_item_table extends Migration
{
    public function up()
    {
        $this->alterColumn('partner_offer', 'subject', $this->string(500));
    }

    public function down()
    {
        $this->alterColumn('partner_offer', 'subject', $this->string(20));
    }

}
