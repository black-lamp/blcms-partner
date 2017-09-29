<?php

use yii\db\Migration;

class m170314_133558_add_partner_offer_fields extends Migration
{
    public function up()
    {
        $this->addColumn('partner_offer', 'subject', $this->string(20));
        $this->addColumn('partner_offer', 'additional_information', $this->string());
    }

    public function down()
    {
        $this->dropColumn('partner_offer', 'subject');
        $this->dropColumn('partner_offer', 'additional_information');
    }
}
