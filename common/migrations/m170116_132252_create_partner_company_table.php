<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_company`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170116_132252_create_partner_company_table extends Migration
{
    const NAME_FIELD_MAX_LENGTH = 500;
    const OFFICIAL_NAME_FIELD_MAX_LENGTH = 500;
    const LOGO_FIELD_MAX_LENGTH = 255;


    /**
     * @var string name of this table
     */
    public $tableName = 'partner_company';
    /**
     * @var string name of user table
     */
    public $userTableName = 'user';
    /**
     * @var string filed name with primary key
     */
    public $userPkField = 'id';
    /**
     * @var string name of city table
     */
    public $cityTableName = 'city';
    /**
     * @var string filed name with primary key
     */
    public $cityPkField = 'id';


    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id'        => $this->primaryKey(),
            'ownerId'   => $this->integer()->notNull(),
            'name'      => $this->string(self::NAME_FIELD_MAX_LENGTH)->notNull(),
            'officialName' => $this->string(self::OFFICIAL_NAME_FIELD_MAX_LENGTH)->notNull(),
            'logo'      => $this->string(self::LOGO_FIELD_MAX_LENGTH),
            'siteLink'  => $this->string(),
            'cityId'    => $this->integer()->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'updatedAt' => $this->integer()->notNull()
        ]);

        $this->addForeignKey(
            $this->tableName . '-' . $this->userTableName . '-FK',
            $this->tableName, 'ownerId',
            $this->userTableName, $this->userPkField
        );

        /*$this->addForeignKey(
            $this->tableName . '-' . $this->cityTableName . '-FK',
            $this->tableName, 'cityId',
            $this->cityTableName, $this->cityPkField
        );*/
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey($this->tableName . '-' . $this->userTableName . '-FK', $this->tableName);
//        $this->dropForeignKey($this->tableName . '-' . $this->cityTableName . '-FK', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
