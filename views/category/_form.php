<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php if (Yii::$app->request->get()['type'] == 'category') : ?>
        <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>
    <?php else : ?>
        <?= $form->field($model, 'category')->widget(\kartik\select2\Select2::className(), [
            'data' => Category::getCategories(false),
            'options' => ['placeholder' => 'Wybierz kategorie'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]) ?>
        <?= $form->field($model, 'subcategory')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'word_category')->textInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Zapisz', ['class' => 'btn float-right px-3 modal-sub']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>