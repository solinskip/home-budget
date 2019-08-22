<?

use yii\helpers\Html;
use app\models\Category;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Transactions */
?>

<div class="transactions-form">
    <? $form = ActiveForm::begin(); ?>

    <div class="mb-3">Przyporządkuj transakcje do <span id="numberOfTransactions"></span> kategorii.</div>

    <?= $form->field($model, 'category_id')->widget(Select2::class, [
        'data' => Category::getCategories(),
        'options' => ['placeholder' => 'Wybierz kategorie...',],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]) ?>

    <?= $form->field($model, 'transactionsId')->textInput()->hiddenInput()->label(false) ?>

    <div class="form-group mt-3">
        <span id="assign-alert" style="color: red; font-size: 14px;"></span>
        <?= Html::submitButton('Zapisz', ['id' => 'assignCategoriesSub', 'class' => 'btn float-right px-3 modal-sub']) ?>
    </div>

    <? ActiveForm::end() ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        let transactionsId = $('#w8').yiiGridView('getSelectedRows');
        $('#numberOfTransactions').text(transactionsId.length);
        $('#transactions-transactionsid').val(transactionsId.join());

        if (transactionsId.length == 0) {
            $('#assign-alert').text('*Należy wybrać przynajmniej jedną transakcje');
            $('#assignCategoriesSub').prop('disabled', true);
        }
    });
</script>