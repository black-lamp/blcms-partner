<?php
namespace bl\cms\partner\frontend\models\forms;

use Exception;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;

use bl\cms\partner\common\entities\CompanyInfo;
use bl\cms\partner\common\entities\PartnerCompany;
use bl\cms\partner\common\entities\CompanyInfoCategory;
use bl\cms\partner\frontend\models\UploadImage;

/**
 * Form for edit company information
 *
 * @property string $name
 * @property string $officialName
 * @property string $site
 * @property string $logo
 * @property array $emails
 * @property array $phones
 * @property UploadImage $uploadedImage
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class EditCompanyInfo extends Model
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $officialName;
    /**
     * @var string
     */
    public $site;
    /**
     * @var string
     */
    public $logo;
    /**
     * @var array
     */
    public $emails;
    /**
     * @var array
     */
    public $phones;

    /**
     * @var integer
     */
    private $_companyId;
    /**
     * @var UploadImage
     */
    private $_uploadedImage;


    /**
     * @inheritdoc
     * @param integer $companyId
     */
    public function __construct($companyId, $config = [])
    {
        $this->_companyId = $companyId;
        $this->_uploadedImage = Yii::$container->get(UploadImage::class);

        parent::__construct($config);
    }

    /**
     * @return UploadImage
     */
    public function getUploadedImage()
    {
        return $this->_uploadedImage;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 500],

            [['officialName'], 'string', 'max' => 500],

            [['site'], 'string', 'max' => 255],

            [['logo'], 'string', 'max' => 255],

            [['emails'], 'isArrayValidation'],

            [['phones'], 'isArrayValidation']
        ];
    }

    /**
     * Array validator
     *
     * @param string $attribute
     * @param array $params
     */
    public function isArrayValidation($attribute, $params)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($this->$attribute, "'$attribute' must be a array");
        }
    }

    /**
     * Serialize [[CompanyInfo]] object to array
     *
     * @param CompanyInfo[] $array
     * @return array
     */
    private static function serializeToArray($array)
    {
        $result = [];
        foreach ($array as $info) {
            $result[] = [
                'id' => $info->id,
                'content' => $info->content
            ];
        }

        return $result;
    }

    /**
     * Serialize array to [[CompanyInfo]] objects
     *
     * @param CompanyInfo[] $objects
     * @param array $array
     * @return CompanyInfo[]
     */
    private static function serializeToObject($objects, $array)
    {
        $objects = ArrayHelper::index($objects, 'id');

        /** @var CompanyInfo[] $result */
        $result = [];
        foreach ($array as $item) {
            if (!empty($item['id'])) {
                $result[$item['id']] = $objects[$item['id']];
                $result[$item['id']]->content = $item['content'];
            }
            else {
                if (!empty($item['content'])) {
                    $result[] = new CompanyInfo([
                        'content' => $item['content']
                    ]);
                }
            }
        }

        return $result;
    }

    /**
     * Build form object from [[Company]] object
     *
     * @param PartnerCompany $company
     * @return $this
     */
    public static function getFromCompany($company)
    {
        return new self($company->id, [
            'name' => $company->name,
            'officialName' => $company->officialName,
            'site' => $company->siteLink,
            'logo' => $company->logo,
            'emails' => self::serializeToArray(CompanyInfo::getEmails($company->id)),
            'phones' => self::serializeToArray(CompanyInfo::getPhones($company->id))
        ]);
    }

    /**
     * Save [[CompanyInfo]] to database
     *
     * @param integer $companyId
     * @param integer $categoryId
     * @param CompanyInfo[] $info
     */
    private function saveInfo($companyId, $categoryId, $info)
    {
        foreach ($info as $object) {
            if (!$object->isNewRecord) {
                $object->update(false);
                continue;
            }

            $object->categoryId = $categoryId;
            $object->companyId = $companyId;
            $object->insert();
        }

        $toDelete = CompanyInfo::find()
            ->select('id')
            ->where([
                'companyId' => $companyId,
                'categoryId' => $categoryId
            ])
            ->andWhere(['not in', 'id', ArrayHelper::getColumn($info, 'id')])
            ->all();

        CompanyInfo::deleteAll(['id' => ArrayHelper::getColumn($toDelete, 'id')]);
    }

    /**
     * Save data from form to database
     *
     * @return boolean returns `true` if data was successfully saved to database
     * @throws Exception
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $transaction = PartnerCompany::getDb()->beginTransaction();
        try {
            /** @var PartnerCompany $company */
            $company = PartnerCompany::findOne($this->_companyId);
            $company->name = $this->name;
            $company->officialName = $this->officialName;
            $company->siteLink = $this->site;
            if ($this->_uploadedImage->file = UploadedFile::getInstance($this->_uploadedImage, 'file')) {
                $company->logo = StringHelper::basename($this->_uploadedImage->upload());
            }

            $company->update(false);

            $emails = self::serializeToObject(CompanyInfo::getEmails($company->id), $this->emails);
            $this->saveInfo($company->id, CompanyInfoCategory::CATEGORY_EMAILS, $emails);

            $phones = self::serializeToObject(CompanyInfo::getPhones($company->id), $this->phones);
            $this->saveInfo($company->id, CompanyInfoCategory::CATEGORY_PHONES, $phones);

            $transaction->commit();

            return true;
        }
        catch (Exception $ex) {
            $transaction->rollBack();
            throw $ex;
        }
    }
}