<?php
use yii\db\Migration;

/**
 * Handles the creation of table `partner_company_info`.
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170116_133116_create_partner_company_info_table extends Migration
{
    /**
     * @var string name of this table
     */
    public $tableName = 'partner_company_info';
    /**
     * @var string name of category info table
     */
    public $categoryTableName = 'partner_company_info_category';
    /**
     * @var string filed name with primary key
     */
    public $categoryPkField = 'id';
    /**
     * @var string name of company table
     */
    public $companyTableName = 'partner_company_info';
    /**
     * @var string filed name with primary key
     */
    public $companyPkField = 'id';


    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable($this->tableName, [
            'id'         => $this->primaryKey(),
            'categoryId' => $this->integer()->notNull(),
            'companyId'  => $this->integer()->notNull(),
            'content'    => $this->text()->notNull()
        ]);

        $this->addForeignKey(
            $this->tableName . '-category-FK',
            $this->tableName, 'categoryId',
            $this->categoryTableName, $this->categoryPkField
        );

        $this->addForeignKey(
            $this->tableName . '-' . $this->companyTableName . '-FK',
            $this->tableName, 'companyId',
            $this->companyTableName, $this->companyPkField
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropForeignKey($this->tableName . '-category-FK', $this->tableName);
        $this->dropForeignKey($this->tableName . '-' . $this->companyTableName . '-FK', $this->tableName);

        $this->dropTable($this->tableName);
    }
}
