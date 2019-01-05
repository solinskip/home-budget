<?php

use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;

?>
<div class="row conversion-form">
    <div class="col-sm-12 text-center">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

        <?= $form->field($model, 'file')->widget(FileInput::classname(), [
            'options' => ['accept' => 'file/*'],
            'pluginOptions' => [
                'allowedFileExtensions' => ['csv'],
                'msgPlaceholder' => 'Wybierz plik...',
                'browseLabel' => '<i class="fas fa-file-csv"></i> Wybierz plik',
                'showUpload' => false,
                'showCancel' => false,
                'showClose' => false,
                'showPreview' => false,
                'showRemove' => false,
            ]
        ])->label(false); ?>

        <?= Html::submitButton('Zapisz dane', ['class' => 'btn modal-sub']) ?>
        <?php ActiveForm::end() ?>
    </div>
</div>