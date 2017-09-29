<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

use bl\cms\partner\frontend\Module as PartnerModule;
use bl\cms\partner\frontend\widgets\PersonalAreaNav;
use bl\cms\partner\common\widgets\UserSearcher;

/**
 * View file for company controller in Partner frontend module
 *
 * @var \yii\web\View $this
 * @var array $users
 * @var integer $companyId
 * @var \bl\cms\partner\common\entities\CompanyEmployee[] $employees
 * @var \bl\cms\cart\common\components\user\models\Profile[] $users
 * @var string $message
 *
 * @author Vladimir Kuprienko <vldmr.kuprienko@gmail.com>
 */

$this->title = PartnerModule::t('employees', 'Employees of the company');
?>
<?php if (!empty($message)): ?>
    <div class="alert alert-success">
        <?= $message ?>
    </div>
<?php endif; ?>
<div class="row">
    <div class="col-md-3">
        <?= PersonalAreaNav::widget() ?>
    </div>
    <div class="col-md-9">
        <h1 class="text-center">
            <?= $this->title ?>
        </h1>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <?php $form = ActiveForm::begin([
                    'action' => ['send-request']
                ]) ?>
                    <?= Html::hiddenInput('companyId', $companyId) ?>
                    <div class="form-group">
                        <?= UserSearcher::widget([
                            'inputName' => 'employeeId',
                            'users' => $users
                        ]) ?>
                    </div>
                    <?= Html::submitButton(
                        PartnerModule::t('employees', 'Send request'),
                        ['class' => 'btn btn-success pull-right']
                    ) ?>
                <?php $form->end() ?>
            </div>
            <?php if (!empty($employees)): ?>
                <div class="col-md-12">
                    <h2 class="text-center">
                        <?= PartnerModule::t('employees', 'Employees list') ?>
                    </h2>
                    <?php foreach ($employees as $employee): ?>
                        <p>
                            <?php if (!empty($employee->user->profile->surname) && !empty($employee->user->profile->name)): ?>
                                <?= Html::encode($employee->user->profile->surname) . ' ' . Html::encode($employee->user->profile->name) ?>
                            <?php else: ?>
                                <?= Html::encode($employee->user->username) ?>
                            <?php endif; ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
