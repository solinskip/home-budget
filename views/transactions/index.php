<?

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\models\Transactions;

$this->title = 'Lista transakcji';

?>
<div class="transactions-index row">
    <div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= DynaGrid::widget([
            'id' => 'crud-datatable',
            'columns' => Transactions::getColumns(),
            'options' => ['id' => 'transactions-grid'],
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => false,
                'striped' => true,
                'condensed' => true,
                'bordered' => false,
                'responsive' => true,
                'resizableColumns' => false,
                'toggleDataContainer' => ['class' => 'btn-group'],
                'toggleDataOptions' => [
                    'all' => [
                        'icon' => ' fas fa-expand-arrows-alt',
                        'label' => '',
                        'class' => 'btn btn-lg',
                        'title' => 'Wyświetl wszystkie',
                    ],
                    'page' => [
                        'icon' => 'fas fa-file',
                        'label' => '',
                        'class' => 'btn btn-lg',
                        'title' => 'Wyświetl stronę',
                    ],
                ],
                'panelTemplate' => $this->render('_panelTemplate'),
            ],
        ]); ?>
    </div>
</div>