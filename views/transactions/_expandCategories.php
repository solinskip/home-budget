<?php

use app\models\Transactions;
use kartik\dynagrid\DynaGrid;

?>

<div class="row" style="margin: auto;">
    <div class="col-lg-12">
        <?= DynaGrid::widget([
            'columns' => [
                [
                    'label' => '',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return $model->name . ' <i class="fas fa-angle-double-right"></i> ' . Yii::$app->formatter->asDecimal(Transactions::expensesCategories($model->id, false), 2) . ' zł';
                    }
                ],
            ],
            'options' => ['class' => 'subcategories-expand-grid'],
            'gridOptions' => [
                'dataProvider' => $dataProvider,
                'striped' => false,
                'condensed' => true,
                'bordered' => false,
                'responsive' => true,
                'resizableColumns' => false,
                'panelTemplate' => '{items}'
            ],
        ]); ?>
    </div>
</div>