<?php

use yii\helpers\Html;

?>

<div class="col-12">
    <div class="row">
        <div class="col-12 col-md-6 my-auto" style="font-size:14px;">{summary}</div>
        <div class="col-12 col-md-auto ml-sm-auto">
            <?= Html::a('<i class="fas fa-plus"></i>', ['create'], ['class' => 'btn btn-lg', 'title' => 'Dodaj transakcję']) ?>
            {toggleData}
        </div>
    </div>
    {items}
    {panelAfter}
    {pager}
</div>