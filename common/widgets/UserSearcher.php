<?php
namespace bl\cms\partner\common\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;

use bl\cms\cart\common\components\user\models\Profile;
use yii2mod\chosen\ChosenSelect;

/**
 * Renders input for searching a users
 *
 * @property Profile[] $users
 * @property string $inputName
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class UserSearcher extends Widget
{
    /**
     * @var Profile[]
     */
    public $users;
    /**
     * @var string
     */
    public $inputName;

    /**
     * @var array
     */
    private $_items;


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->_items = ArrayHelper::map(
            $this->users,
            'user_id',
            function ($model) {
                return sprintf("%s %s %s", $model['surname'], $model['name'], $model['patronymic']);
            }
        );
    }

    /**
     * @inheritdoc
     * @see https://github.com/yii2mod/yii2-chosen-select
     */
    public function run()
    {
        echo ChosenSelect::widget([
            'name'  => $this->inputName,
            'items' => $this->_items
        ]);
    }
}