<?php

use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model \app\models\User */
/* @var $usersById array */
/* @var $action array */
/* @var $modalTitle string */
/* @var $submitButtonLabel string */
?>

<div class="modal fade" id="userFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php
            $form = ActiveForm::begin([
                    'id' => 'user-form',
                    'action' => $action,
                    'method' => 'post',
                    'enableClientValidation' => true,
            ]); ?>

            <div class="modal-header">
                <h5 class="modal-title"><?= Html::encode($modalTitle) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'manager_id')->dropDownList(
                        ArrayHelper::map($usersById, 'id', fn($u) => $u->first_name . ' ' . $u->last_name),
                        ['prompt' => 'Select manager (optional)']
                ) ?>
                <?= $form->field($model, 'access_level')->input('number') ?>
                <?= $form->field($model, 'birthday')->input('date') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
            </div>

            <div class="modal-footer">
                <?= Html::submitButton($submitButtonLabel, ['class' => 'btn btn-success']) ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>

            <?php
            ActiveForm::end(); ?>
        </div>
    </div>
</div>
