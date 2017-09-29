<?php
namespace bl\cms\partner\frontend\models\forms;

use bl\cms\partner\common\base\PartnerModule;
use yii\base\Model;

/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 */
class CommercialOfferMail extends Model
{

    /**
     * @var integer
     */
    public $offerId;

    /**
     * Subject of email
     * @var string
     */
    public $subject;

    /**
     * @var string
     */
    public $additionalInformation;

    public function rules()
    {
        return [
            [['offerId'], 'integer'],
            [['subject'], 'string', 'length' => [5, 500]],
            [['additionalInformation'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => \Yii::t('partner.offer', 'Subject'),
            'additionalInformation' => \Yii::t('partner.offer', 'Additional information')
        ];
    }
}