<?php
namespace bl\cms\partner\frontend;

use Yii;
use yii\helpers\Url;

use bl\cms\partner\common\base\PartnerModule;
use bl\cms\partner\frontend\models\UploadImage;

use bl\legalAgreement\common\components\LegalManager;

/**
 * Partner frontend module definition class
 *
 * @property string $imagesRootUrl
 * @property string $imagesRoot
 * @property string $imagePrefix
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class Module extends PartnerModule
{
    const MODULE_ID = 'partner';
    const LEGAL_MANAGER_COMPONENT_ID = 'legalManager';


    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'bl\cms\partner\frontend\controllers';
    /**
     * @var string
     */
    public $imagesRootUrl = '/images/partner/logos';
    /**
     * @var string
     */
    public $imagesRoot = '@frontend/web/images/partner/logos';
    /**
     * @var string
     */
    public $imagePrefix = 'logo';


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->setComponents([
            self::LEGAL_MANAGER_COMPONENT_ID => [
                'class' => LegalManager::class
            ]
        ]);

        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function registerDependencies()
    {
        parent::registerDependencies();

        Yii::$container->set(UploadImage::class, new UploadImage($this->imagePrefix, $this->imagesRoot));
    }

    /**
     * Get [[LegalManager]] component from module
     *
     * @return LegalManager
     */
    public function getLegalManager()
    {
        return $this->get(self::LEGAL_MANAGER_COMPONENT_ID);
    }

    /**
     * Create URL from to controllers of this module
     *
     * @param string|array $url
     * @param boolean $scheme
     * @return string
     */
    public static function toRoute($url, $scheme = false)
    {
        if (is_array($url)) {
            $url[0] = '/' . self::MODULE_ID . '/' . $url[0];
            return Url::toRoute($url, $scheme);
        }

        return Url::toRoute(['/' . self::MODULE_ID . '/' . $url], $scheme);
    }

    /**
     * @inheritdoc
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('partner.frontend.' . $category, $message, $params, $language);
    }
}