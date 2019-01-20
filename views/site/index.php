<?php

use yii\helpers\Url;
use app\assets\ChartAsset;
use app\models\Transactions;
use kartik\grid\GridView;
use kartik\dynagrid\DynaGrid;

ChartAsset::register($this);

$this->title = 'Miesięczne zestawienie statystyk';

?>
    <div class="site-index row">
        <div class="col-md-12">
            <?php if (!Yii::$app->user->isGuest) : ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <div class="display-4">Zestawienie miesięcznych statystyk</div>
                    </div>
                </div>
                <div class="row mt-5 justify-content-md-center">
                    <div class="col-md-4">
                        <?= DynaGrid::widget([
                            'columns' => [
                                [
                                    'label' => '',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->name . ' <i class="fas fa-angle-double-right"></i> ' . Yii::$app->formatter->asDecimal(Transactions::expensesCategories($model->id, false), 2) . ' zł';
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
                    <div class="col-md-8" style="margin-top: 3%">
                        <div class="row ml-1">
                            <div class="col-md-6 statistic-text">Zalogowany użytkownik:</div>
                            <h4>
                                <span class="badge user-label"><?= ucfirst(Yii::$app->user->identity->username) ?></span>
                            </h4>
                        </div>
                        <div class="row ml-1">
                            <div class="col-md-6 statistic-text">Suma wydatków w tym miesiącu:</div>
                            <h4>
                                <span class="badge monthly-expenses-label"><?= Yii::$app->formatter->asDecimal(Transactions::monthlyExpenses()) ?> zł</span>
                            </h4>
                        </div>
                        <div class="row ml-1">
                            <div class="col-md-6 statistic-text">Ostatnia aktualizacja danych:</div>
                            <h4><span class="badge last-upload-label"><?= \app\models\User::lastUpload() ?></span></h4>
                        </div>
                        <div class="row mt-4">
                            <canvas id="canvas" class="col-md-12"></canvas>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="display-4 text-center">Witaj w aplikacji <span class="text-secondary"
                                                                           style="font-weight: 400">Budżet domowy</span>
                </div>
                <div class="text-center display-4 mt-4" style="font-size: 35px">Aby zaczać korzystać z aplikacji
                    <span class="badge badge-success">zaloguj się</span> lub <span
                            class="badge badge-info">zarejestruj</span>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
if (!Yii::$app->user->isGuest) :
    $balance = json_encode(Transactions::monthlyExpansesPerDay());
    $script = <<< JS
    
    //config for chartJS
    let balance = {$balance};
    let config = {
        type: 'line',
        data: {
            labels: balance.date,
            datasets: [{
                label: 'Bilans kosztów',
                backgroundColor: '#ffc107',
                borderColor: '#ffc107',
                data: balance.balance,
                fill: false,
            }]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Analiza przychodów i kosztów',
                fontSize: 20,
            },
            tooltips: {
                callbacks: {
                    title: function (tooltipItem) {
                        return 'Dzień: ' + tooltipItem['0']['xLabel'];
                    },
                    label: function (tooltipItem) {
                        return 'Łączna suma: ' + tooltipItem['yLabel'] + ' zł';
                    },
                },
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Dzień miesiąca'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Suma kosztów'
                    }
                }]
            }
        }
    };

    //draw chart in canvas field
    window.onload = function () {
        let ctx = document.getElementById('canvas').getContext('2d');
        window.myLine = new Chart(ctx, config);
    };
JS;

    $this->registerJs($script);
endif;