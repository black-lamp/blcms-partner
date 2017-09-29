<?php
namespace bl\cms\partner\frontend\widgets;

use Yii;
use yii\widgets\Menu;

use bl\cms\partner\frontend\Module as PartnerModule;

/**
 * Widget renders the navigation for partner personal area
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class PersonalAreaNav extends Menu
{
    public function init()
    {
        if (empty($this->items)) {
            $this->items = [
                [
                    'label' => PartnerModule::t('personal-area.nav', 'Company'),
                    'url' => [
                        Yii::$app->user->can('editCompanyInfo') ? '/partner/company/edit' : '/partner/company/view'
                    ],
                    'visible' => (Yii::$app->user->can('seeCompanyInfo') || Yii::$app->user->can('editCompanyInfo'))
                ],
                [
                    'label' => PartnerModule::t('personal-area.nav', 'Employees'),
                    'url' => ['/partner/company/employees'],
                    'visible' => Yii::$app->user->can('employeesManage')
                ],
                [
                    'label' => PartnerModule::t('personal-area.nav', 'Site'),
                    'url' => ['/partner/company/site-request'],
                    'visible' => Yii::$app->user->can('siteManage')
                ],
                [
                    'label' => PartnerModule::t('personal-area.nav', 'Marketing materials'),
                    'url' => ['/partner/materials/index'],
                    'visible' => Yii::$app->user->can('marketingManage')
                ],
                [
                    'label' => PartnerModule::t('personal-area.nav', 'Commercial offers'),
                    'url' => ['/partner/offer/list'],
                    'visible' => Yii::$app->user->can('seeCommercialOffers')
                ],
                [
                    'label' => PartnerModule::t('personal-area.nav', 'Guarantee registration'),
                    'url' => ['/partner/guarantee/index'],
                    'visible' => Yii::$app->user->can('guaranteeRegistration')
                ],
            ];

        }
    }
}
