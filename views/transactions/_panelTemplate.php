<?php

use yii\helpers\Html;

?>

<div class="col-12">
    <div class="row">
        <div class="col-12 col-md-6 my-auto" style="font-size:14px;">{summary}</div>
        <div class="col-12 col-md-auto ml-sm-auto">
            <?= Html::a('Przypisz kategorie', FALSE, ['id' => 'assign-categories', 'value' => 'bulk-assign-category', 'class' => 'loadAjaxContent btn btn-success', 'style' => 'color: white; background-color: #28a745; border-color: #28a745', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Przyporządkuj kategorie']); ?>
            <?= Html::a('<i class="fas fa-plus"></i>', FALSE, ['value' => 'create', 'class' => 'loadAjaxContent btn btn-lg', 'style' => 'color: #4285F4', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Dodaj transakcje']); ?>
            {toggleData}
        </div>
    </div>
    {items}
    {panelAfter}
    {pager}
</div>