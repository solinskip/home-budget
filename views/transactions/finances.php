<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\dynagrid\DynaGrid;
use app\models\Transactions;

$this->title = 'Moje finanse';
?>
<div class="transactions-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <?= DynaGrid::widget([
        'columns' => Transactions::getColumns(),
        'options' => ['id' => 'finances-grid'],
        'gridOptions' => [
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
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
    <?php Pjax::end(); ?>
</div>