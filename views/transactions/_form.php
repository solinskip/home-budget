<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transactions-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'date')->widget(DateControl::classname(), [
        'type' => DateControl::FORMAT_DATE,
        'ajaxConversion' => false,
        'widgetOptions' => [
            'pluginOptions' => [
                'autoclose' => true,
            ]
        ]
    ]) ?>
    <?= $form->field($model, 'name_sender')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name_recipient')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'transaction_detail')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Zapisz' : 'Aktualizuj', ['class' => 'btn float-right px-3 modal-sub']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>