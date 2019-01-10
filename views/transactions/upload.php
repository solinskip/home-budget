<?php

use yii\bootstrap\Html;
use kartik\form\ActiveForm;
use kartik\file\FileInput;

$this->title = 'Wgraj pliki';

?>
<div class="row conversion-form">
    <div class="col-sm-12">
        <h1>Prześlij dane</h1>
        <p>Wybierz plik z transakcjami do wgrania na serwer, przyjmowane rozszerzenie pliku to <span class="badge badge-warning">.csv</span></p>
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

        <div class="text-center">
            <?= Html::submitButton('Wgraj dane', ['class' => 'btn modal-sub']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>