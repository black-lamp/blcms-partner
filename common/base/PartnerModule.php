<?php
namespace bl\cms\partner\common\base;

use bl\cms\partner\frontend\components\PriceCalculator;
use Yii;
use yii\base\Module as YiiModule;

use bl\cms\partner\common\components\CompanyManager;
use bl\cms\partner\common\components\FileManager;
use bl\cms\partner\common\components\PartnerMailer;
use bl\cms\partner\common\components\User;

use bl\emailTemplates\components\TemplateManager;

/**
 * Base Partner module
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
abstract class PartnerModule extends YiiModule
{
    const MAILER_COMPONENT_ID = 'mailer';
    const USER_COMPONENT_ID = 'user';
    const COMPANY_COMPONENT_ID = 'companyManager';
    const FILE_MANAGER_COMPONENT_ID = 'materialsFileManager';
    const PRICE_CALCULATOR_COMPONENT_ID = 'priceCalculator';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setComponents([
            self::USER_COMPONENT_ID      => ['class' => User::class],
            self::COMPANY_COMPONENT_ID   => ['class' => CompanyManager::class],
            self::FILE_MANAGER_COMPONENT_ID => [
                'class' => FileManager::class,
                'filesRoot' => '@frontend/web/files/materials',
                'filePrefix' => 'material'
            ],
            self::PRICE_CALCULATOR_COMPONENT_ID => [
                'class' => PriceCalculator::class
            ],
        ]);

        $this->registerDependencies();
    }

    /**
     * Register dependencies to DI container
     */
    protected function registerDependencies()
    {
        Yii::$container->set(PartnerMailer::class, [], [
            'templateManager' => new TemplateManager()
        ]);
    }

    /**
     * Get [[PriceCalculator]] component from module
     *
     * @return PriceCalculator
     */
    public function getCalculator()
    {
        return $this->get(self::PRICE_CALCULATOR_COMPONENT_ID);
    }

    /**
     * Get [[PartnerMailer]] component from module
     *
     * @return PartnerMailer
     */
    public function getMailer()
    {
        return Yii::$container->get(PartnerMailer::class);
    }

    /**
     * Get [[User]] component from module
     *
     * @return User
     */
    public function getUserComponent()
    {
        return $this->get(self::USER_COMPONENT_ID);
    }

    /**
     * Get [[CompanyManager]] component
     *
     * @return CompanyManager
     */
    public function getCompanyManager()
    {
        return $this->get(self::COMPANY_COMPONENT_ID);
    }

    /**
     * Get [[FileManager]] component from module
     *
     * @return FileManager
     */
    public function getFileManager()
    {
        return $this->get(self::FILE_MANAGER_COMPONENT_ID);
    }

    /**
     * Wrapper for default method `Yii::t()`
     *
     * @param string $category
     * @param string $message
     * @param array $params
     * @param null $language
     * @return string returns result of `Yii::t()` method
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('partner.' . $category, $message, $params, $language);
    }
}