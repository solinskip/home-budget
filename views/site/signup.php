<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;

?>

<div class="row singup-form ajax-form">
    <div class="col-sm-10 offset-1">
        <?php Pjax::begin(['id' => 'form-signup-pjax']) ?>

        <?php $form = ActiveForm::begin([
            'id' => 'form-signup',
            'enableAjaxValidation' => true,
            'validationUrl' => Url::to(['site/validate-form', 'model' => get_class($model)]),
            'errorCssClass' => 'text-danger',
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput(['autocomplete' => 'new-password']) ?>

        <hr>

        <?= Html::submitButton('Rejestracja', ['class' => 'btn float-right px-3 modal-sub']) ?>

        <?php ActiveForm::end(); ?>

        <?php Pjax::end() ?>
    </div>
</div>