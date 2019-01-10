<?php

use yii\helpers\Url;
use kartik\dynagrid\DynaGrid;
use kartik\grid\GridView;
use app\models\Transactions;

$this->title = '';

?>
<div class="site-index row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="text-center"><?= 'Witaj ' . Yii::$app->user->identity->username ?></h2>
                <h3>Zestawienie miesięcznych statystyk</h3>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-md-4">
                <?= DynaGrid::widget([
                    'columns' => [
                        [
                            'label' => '',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->name . ' <i class="fas fa-angle-double-right"></i> ' . Yii::$app->formatter->asDecimal(Transactions::expensesCategories($model->id), 2) . ' zł';
                            },
                        ],
                        [
                            'class' => 'kartik\grid\ExpandRowColumn',
                            'value' => function () {
                                return GridView::ROW_COLLAPSED;
                            },
                            'expandIcon' => '<i class="fas fa-angle-down"></i>',
                            'collapseIcon' => '<i class="fas fa-angle-up"></i>',
                            'detailUrl' => Url::to(['/category/expand-category']),
                            'allowBatchToggle' => false,
                            'width' => '30px',
                        ],
                    ],
                    'options' => ['class' => 'month-statistic-grid'],
                    'gridOptions' => [
                        'dataProvider' => $dataProvider,
                        'toolbar' => false,
                        'striped' => false,
                        'condensed' => true,
                        'responsive' => true,
                        'panel' => [
                            'type' => 'primary',
                            'heading' => '<i class="glyphicon glyphicon-list"></i> ' . 'Wydatki na kategorie',
                            'after' => false,
                            'before' => false,
                            'footer' => false,
                        ],
                        'summary' => false,
                    ],
                ]); ?>
            </div>
            <div class="col-md-6">
                <h3>Wydano w tym miesiącu: <?= Yii::$app->formatter->asDecimal(Transactions::monthlyExpenses()) ?>
                    zł</h3>
                <h3>Ostatnia aktualizacja danych:</h3>
            </div>
        </div>
    </div>
</div>