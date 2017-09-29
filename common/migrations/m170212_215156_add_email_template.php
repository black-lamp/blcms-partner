<?php
use yii\db\Migration;

use bl\emailTemplates\models\entities\EmailTemplate;

/**
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170212_215156_add_email_template extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->insert('{{%email_template}}', [
            'key' => 'employee-confirmed'
        ]);

        $templateId = EmailTemplate::findOne(['key' => 'employee-confirmed'])->id;

        $this->insert('{{%email_template_translation}}', [
            'template_id' => $templateId,
            'language_id' => 1,
            'subject' => 'new add employee request',
            'body' => '{employee-name} {employee-list-link}'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $templateId = EmailTemplate::findOne(['key' => 'employee-confirmed'])->id;

        $this->delete('{{%email_template}}', "id = $templateId");
        $this->delete('{{%email_template_translation}}', "template_id = $templateId");

        return true;
    }
}
