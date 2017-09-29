<?php
namespace bl\cms\partner\frontend\models\forms;

use Yii;
use yii\base\Exception;

use bl\cms\partner\common\entities\UserRequest;

/**
 * Model for partner request form
 *
 * @property integer $city
 * @property string $companyName
 * @property string $officialCompanyName
 * @property string $site
 * @property boolean $acceptLegalAgreement
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class PartnerRequest extends \yii\base\Model
{
    /**
     * @var integer
     */
    public $city;
    /**
     * @var string name of a company
     */
    public $companyName;
    /**
     * @vat string official company name
     */
    public $officialCompanyName;
    /**
     * @var string site link
     */
    public $site;
    /**
     * @var boolean flag is user accepted the legal agreement
     */
    public $acceptLegalAgreement;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyName'], 'required'],
            [['companyName'], 'string', 'max' => 500],

            [['officialCompanyName'], 'string', 'max' => 500],

            [['site'], 'string'],

            [['city'], 'string', 'max' => 255],

            [['acceptLegalAgreement'], 'required'],
            [['acceptLegalAgreement'], 'boolean'],
            [['acceptLegalAgreement'], 'isTrue'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'city' => Yii::t('partner.request', 'City'),
            'companyName' => Yii::t('partner.request', 'Company name'),
            'officialCompanyName' => Yii::t('partner.request', 'Official company name'),
            'site' => Yii::t('partner.request', 'Site'),
            'acceptLegalAgreement' => Yii::t('partner.request', 'I accept legal agreement'),
        ];
    }

    public function isTrue($attribute)
    {
        if (!$this->$attribute) {
            $this->addError(
                $attribute,
                Yii::t('partner.validation', 'You must accept the legal agreement')
            );
        }
    }

    // TODO: PHPDoc
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $request = new UserRequest([
            'userId' => Yii::$app->user->id,
            'city' => $this->city,
            'companyName' => $this->companyName,
            'officialCompanyName' => $this->officialCompanyName,
            'site' => $this->site
        ]);

        try {
            return $request->insert();
        }
        catch (Exception $ex) {
            // TODO: exception info to log
            throw $ex;
        }
    }
}