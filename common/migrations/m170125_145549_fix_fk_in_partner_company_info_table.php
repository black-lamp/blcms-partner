<?php
use yii\db\Migration;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170125_145549_fix_fk_in_partner_company_info_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('partner_company_info-partner_company_info-FK', 'partner_company_info');
        $this->addForeignKey(
            'partner_company_info-partner_company-FK',
            'partner_company_info', 'companyId',
            'partner_company', 'id'
        );
    }

    public function down()
    {
        echo "m170125_145549_fix_fk_in_partner_company_info_table cannot be reverted.\n";

        return true;
    }
}
