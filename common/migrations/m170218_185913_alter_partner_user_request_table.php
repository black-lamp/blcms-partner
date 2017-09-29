<?php
use yii\db\Migration;

class m170218_185913_alter_partner_user_request_table extends Migration
{
    const CITY_FIELD_MAX_LENGTH = 255;


    /**
     * @var string Table name
     */
    public $tableName = 'partner_user_request';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropColumn($this->tableName, 'cityId');
        $this->addColumn($this->tableName, 'city', $this->string(self::CITY_FIELD_MAX_LENGTH));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn($this->tableName, 'city');
        $this->addColumn($this->tableName, 'cityId', $this->integer());

        return true;
    }
}
