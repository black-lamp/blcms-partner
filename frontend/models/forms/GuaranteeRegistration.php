<?php
namespace bl\cms\partner\frontend\models\forms;

use Yii;
use yii\base\Model;

use bl\cms\partner\frontend\Module as PartnerModule;

/**
 * Form for registration of guarantee
 *
 * @property string $name
 * @property string $productSku
 * @property string $companyName
 * @property string $blankNumber
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class GuaranteeRegistration extends Model
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $productSku;
    /**
     * @var string
     */
    public $companyName;
    /**
     * @var string
     */
    public $blankNumber;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'productSku', 'companyName', 'blankNumber'], 'required'],
            [['name', 'productSku', 'companyName', 'blankNumber'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'        => Yii::t('partner.frontend.guarantee', 'Name'),
            'productSku'  => Yii::t('partner.frontend.guarantee', 'Product SKU'),
            'companyName' => Yii::t('partner.frontend.guarantee', 'Company name'),
            'blankNumber' => Yii::t('partner.frontend.guarantee', 'Blank number'),
        ];
    }

    /**
     * Send data from form to email
     *
     * @return boolean returns `true` if data was successfully sended
     */
    public function send()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var PartnerModule $module */
        if ($module = PartnerModule::getInstance()) {
            return $module->getMailer()->sendGuaranteeData(
                $this->name,
                $this->productSku,
                $this->companyName,
                $this->blankNumber
            );
        }

        return false;
    }
}