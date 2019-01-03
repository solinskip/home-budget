<?php

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\models\Transactions;
use johnitvn\ajaxcrud\CrudAsset;

CrudAsset::register($this);

$this->title = 'Moje finanse';
?>
<div class="transactions-index row">
    <div id="ajaxCrudDatatable col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= DynaGrid::widget([
            'id' => 'crud-datatable',
            'columns' => Transactions::getColumns(),
            'options' => ['id' => 'finances-grid'],
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'pjax' => true,
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