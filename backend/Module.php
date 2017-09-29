<?php
namespace bl\cms\partner\backend;

use Yii;

use bl\cms\partner\common\base\PartnerModule;

/**
 * Partner backend module definition class
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class Module extends PartnerModule
{
    const MODULE_ID = 'partner';


    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'bl\cms\partner\backend\controllers';
    /**
     * @inheritdoc
     */
    public $defaultRoute = 'company';


    /**
     * @inheritdoc
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('partner.backend.' . $category, $message, $params, $language);
    }
}