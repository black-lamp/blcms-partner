<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_user_request`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170111_112753_create_partner_user_request_table extends Migration
{
    const TABLE_NAME = 'partner_user_request';

    const NAME_MAX_LENGTH = 500;

    const STATUS_ON_CONFIRMATION = 1;

    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable(self::TABLE_NAME, [
            'id'          => $this->primaryKey(),
            'userId'      => $this->integer()->notNull(),
            'cityId'      => $this->integer(),
            'statusId'    => $this->integer()->notNull()->defaultValue(self::STATUS_ON_CONFIRMATION),
            'companyName' => $this->string(self::NAME_MAX_LENGTH)->notNull(),
            'createdAt'   => $this->integer()->notNull(),
            'moderatedAt' => $this->integer()->notNull()
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
