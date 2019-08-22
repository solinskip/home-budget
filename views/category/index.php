<?

use yii\helpers\Html;
use kartik\dynagrid\DynaGrid;
use app\models\Category;

$this->title = 'Kategorie finansów';
?>
<div class="row">
    <div class="category-list col-md-8">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= DynaGrid::widget([
            'columns' => Category::getColumns(),
            'options' => ['id' => 'category-grid'],
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