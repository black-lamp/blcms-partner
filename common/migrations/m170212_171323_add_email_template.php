<?php
use yii\db\Migration;

use bl\emailTemplates\models\entities\EmailTemplate;

/**
 * Add email template
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class m170212_171323_add_email_template extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->insert('{{%email_template}}', [
            'key' => 'add-employee-notification'
        ]);
        $templateId = EmailTemplate::findOne(['key' => 'add-employee-notification'])->id;
        $this->insert('{{%email_template_translation}}', [
            'template_id' => $templateId,
            'language_id' => 1,
            'subject' => 'add employee request',
            'body' => '{employee-name} {company-name} {company-owner} {moderation-link}'
        ]);

        $this->insert('{{%email_template}}', [
            'key' => 'partner-after-moderation'
        ]);
        $templateId = EmailTemplate::findOne(['key' => 'partner-after-moderation'])->id;
        $this->insert('{{%email_template_translation}}', [
            'template_id' => $templateId,
            'language_id' => 1,
            'subject' => 'request to be a partner',
            'body' => 'Your request has been successfully accepted. Go to {personal-area-link}'
        ]);

        $this->insert('{{%email_template}}', [
            'key' => 'guarantee-form'
        ]);
        $templateId = EmailTemplate::findOne(['key' => 'guarantee-form'])->id;
        $this->insert('{{%email_template_translation}}', [
            'template_id' => $templateId,
            'language_id' => 1,
            'subject' => 'guarantee registration',
            'body' => 'Name: {name}\n Product SKU: {sku}\n Company name: {company-name}\n Blank number: {blank-number}\n'
        ]);

        $this->insert('{{%email_template}}', [
            'key' => 'legal-agreement-employees'
        ]);
        $templateId = EmailTemplate::findOne(['key' => 'legal-agreement-employees'])->id;
        $this->insert('{{%email_template_translation}}', [
            'template_id' => $templateId,
            'language_id' => 1,
            'subject' => 'legal agreement',
            'body' => 'Your has been invited to partner company. For accept this invite - you must accept the legal agreement {agreement-link}'
        ]);

        $this->insert('{{%email_template}}', [
            'key' => 'partner-after-moderation-decline'
        ]);
        $templateId = EmailTemplate::findOne(['key' => 'partner-after-moderation-decline'])->id;
        $this->insert('{{%email_template_translation}}', [
            'template_id' => $templateId,
            'language_id' => 1,
            'subject' => 'request to be a partner',
            'body' => 'Your request has been declined.'
        ]);

        $this->insert('{{%email_template}}', [
            'key' => 'company-owner-add-employee'
        ]);
        $templateId = EmailTemplate::findOne(['key' => 'company-owner-add-employee'])->id;
        $this->insert('{{%email_template_translation}}', [
            'template_id' => $templateId,
            'language_id' => 1,
            'subject' => 'added employee to your company',
            'body' => 'To your company has been added a new employee. {employee-list-link}'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $templateId = EmailTemplate::findOne(['key' => 'add-employee-notification'])->id;
        $this->delete('{{%email_template}}', "id = $templateId");
        $this->delete('{{%email_template_translation}}', "template_id = $templateId");

        $templateId = EmailTemplate::findOne(['key' => 'partner-after-moderation'])->id;
        $this->delete('{{%email_template}}', "id = $templateId");
        $this->delete('{{%email_template_translation}}', "template_id = $templateId");

        $templateId = EmailTemplate::findOne(['key' => 'guarantee-form'])->id;
        $this->delete('{{%email_template}}', "id = $templateId");
        $this->delete('{{%email_template_translation}}', "template_id = $templateId");

        $templateId = EmailTemplate::findOne(['key' => 'legal-agreement-employees'])->id;
        $this->delete('{{%email_template}}', "id = $templateId");
        $this->delete('{{%email_template_translation}}', "template_id = $templateId");

        $templateId = EmailTemplate::findOne(['key' => 'partner-after-moderation-decline'])->id;
        $this->delete('{{%email_template}}', "id = $templateId");
        $this->delete('{{%email_template_translation}}', "template_id = $templateId");

        $templateId = EmailTemplate::findOne(['key' => 'company-owner-add-employee'])->id;
        $this->delete('{{%email_template}}', "id = $templateId");
        $this->delete('{{%email_template_translation}}', "template_id = $templateId");

        return true;
    }
}
