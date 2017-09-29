<?php
namespace bl\cms\partner\backend\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;

use bl\cms\partner\common\entities\ModerationStatus;

/**
 * Widget for displaying count of request to be a partner
 * or add employee to the company
 *
 * @property string $entity
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */
class RequestsCount extends Widget
{
    /**
     * @var string
     */
    public $entity = null;


    /**
     * @inheritdoc
     */
    public function init()
    {
        if (empty($this->entity)) {
            throw new InvalidConfigException('You should set a $entity property');
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var ActiveRecord $className */
        $className = $this->entity;

        $count = $className::find()
            ->where(['statusId' => ModerationStatus::STATUS_ON_MODERATION])
            ->count('id');

        return $this->render('counter', [
            'count' => $count
        ]);
    }
}