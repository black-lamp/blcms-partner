<?php
namespace bl\cms\partner\frontend\models\forms;

use Yii;
use yii\base\Model;

use bl\cms\partner\common\entities\SubsiteRequest;

/**
 * @property integer $companyId
 * @property string $domainName
 * @property boolean $hasHosting
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class SubsiteRequestForm extends Model
{
    const SCENARIO_PG_POOL_DOMAIN = 'pgPoolDomain';
    const SCENARIO_MY_DOMAIN = 'myDomain';


    /**
     * @var integer
     */
    public $companyId;
    /**
     * @var string
     */
    public $domainName;
    /**
     * @var boolean
     */
    public $hasHosting = null;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companyId', 'domainName'], 'required'],
            [['companyId'], 'integer'],
            [['hasHosting'], 'boolean'],

            [['domainName'], 'string', 'max' => 255, 'on' => self::SCENARIO_MY_DOMAIN],

            [['domainName'], 'string', 'max' => 20, 'on' => self::SCENARIO_PG_POOL_DOMAIN],
            [['domainName'], 'uniqueDomain', 'on' => self::SCENARIO_PG_POOL_DOMAIN],
        ];
    }

    /**
     * Validator for domain name
     *
     * @param string $attribute
     */
    public function uniqueDomain($attribute, $params)
    {
        $isExists = SubsiteRequest::find()
            ->where(['domainName' => $this->domainName])
            ->exists();
        if ($isExists) {
            $this->addError($attribute, Yii::t('partner.entity', 'Domain name is already exists'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('partner.entity', 'ID'),
            'companyId'  => Yii::t('partner.entity', 'Company ID'),
            'domainName' => Yii::t('partner.entity', 'Domain name'),
            'hasHosting' => Yii::t('partner.entity', 'I have a hosting'),
        ];
    }

    /**
     * @param integer $companyId
     * @param string $scenario
     * @return SubsiteRequestForm
     */
    public static function buildById($companyId, $scenario)
    {
        return new self([
            'companyId' => $companyId,
            'scenario' => $scenario
        ]);
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $request = new SubsiteRequest([
            'companyId'  => $this->companyId,
            'domainName' => $this->domainName,
            'hasHosting' => $this->hasHosting
        ]);

        return $request->insert(false);
    }
}