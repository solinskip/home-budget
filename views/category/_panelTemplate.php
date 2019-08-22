<?

use yii\helpers\Url;
use yii\helpers\Html;

?>

<div class="col-12">
    <div class="row">
        <div class="col-12 col-md-6 my-auto" style="font-size:14px;">{summary}</div>
        <div class="col-12 col-md-auto ml-sm-auto">
            <?= Html::a('<i class="fas fa-square"></i>', FALSE, ['value' => Url::to(['create', 'type' => 'category']), 'class' => 'loadAjaxContent btn btn-lg', 'style' => 'color: #4285F4', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Dodaj kategorie', 'title' => 'Stwórz kategorie']); ?>
            <?= Html::a('<i class="fas fa-sitemap"></i>', FALSE, ['value' => Url::to(['create', 'type' => 'subcategory']), 'class' => 'loadAjaxContent btn btn-lg', 'style' => 'color: #4285F4', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Dodaj subkategorie', 'title' => 'Stwórz podkategorie']); ?>
            {toggleData}
        </div>
    </div>
    {items}
    {panelAfter}
    {pager}
</div>