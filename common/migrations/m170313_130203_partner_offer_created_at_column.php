<?php

use yii\db\Migration;

/**
 * Handles the creation of table `partner_offer_own_item`.
 */
class m170313_130203_partner_offer_created_at_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('partner_offer', 'created_at', $this->dateTime());
        $this->addColumn('partner_offer', 'updated_at', $this->dateTime());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('partner_offer', 'created_at');
        $this->dropColumn('partner_offer', 'updated_at');
    }
}
