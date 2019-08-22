<?

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use kartik\form\ActiveForm;

?>

<div class="row login-form ajax-form">
    <div class="col-sm-10 offset-1">
        <? Pjax::begin(['id' => 'form-login-pjax']) ?>

        <? $form = ActiveForm::begin([
            'id' => 'form-login',
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'validationUrl' => Url::to(['validate-form', 'model' => get_class($model)]),
            'errorCssClass' => 'text-danger',
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>

        <hr>

        <?= Html::submitButton('Zaloguj się', ['class' => 'btn float-right px-3 modal-sub']) ?>

        <? ActiveForm::end(); ?>

        <? Pjax::end() ?>
    </div>
</div>