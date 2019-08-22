<?

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property string $description
 * @property string $word_category
 */
class Category extends \yii\db\ActiveRecord
{
    //virtual variables
    public $category;
    public $subcategory;

    public static function tableName()
    {
        return 'category';
    }

    public function rules()
    {
        return [
            [['name', 'parent'], 'required'],
            [['parent'], 'integer'],
            [['name', 'category', 'subcategory', 'description', 'word_category'], 'string', 'max' => 255],
            [['name', 'word_category'], 'unique'],

            //scenarios
            [['category'], 'required', 'on' => 'category'],
            [['category', 'subcategory'], 'required', 'on' => 'subcategory'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nazwa',
            'parent' => 'Kategoria główna',
            'description' => 'Opis',
            'word_category' => 'Słowa filtrujące',
            //virtual labels
            'category' => 'Kategoria',
            'subcategory' => 'Podkategoria'
        ];
    }

    /**
     * Columns to category dynagrid
     *
     * @return array
     */
    public function getColumns()
    {
        return [
            [
                'attribute' => 'category',
                'label' => 'Kategoria',
                'value' => function ($model) {
                    if ($model->parent !== 0) {
                        $category = Category::findOne($model->parent);
                    }
                    return isset($category) ? $category->name : '';
                },
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'width' => '180px',
                'groupOddCssClass' => 'group-odd',
                'groupEvenCssClass' => 'group-even',
                'group' => true,  // enable grouping
            ],
            [
                'attribute' => 'subcategory',
                'label' => 'Podkategoria',
                'value' => function ($model) {
                    return $model->parent !== 0 ? $model->name : '';
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => ArrayHelper::map(Category::find()->where(['<>', 'parent', 0])->orderBy('name')->asArray()->all(), 'name', 'name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => ''],
                'width' => '180px',
            ],
            'description',
            'word_category',
            [
                'class' => 'kartik\grid\ActionColumn',
                'header' => 'Akcje',
                'dropdown' => false,
                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::to([$action, 'id' => $key]);
                },
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="fa fa-edit" style="color:#222d32;"></span>', false, ['value' => Url::to(['update', 'id' => $model->id, 'type' => ($model->parent == '0' ? 'category' : 'subcategory')]), 'class' => 'loadAjaxContent', 'style' => 'cursor: pointer', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Aktualizuj transakcje']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="fas fa-trash-alt" style="color:#222d32;"></span>', Url::to(['delete', 'id' => $model->id, 'type' => ($model->parent == '0' ? 'category' : 'subcategory')]), ['data-pjax' => 0, 'title' => 'Usuń',
                            'data' => [
                                'data-confirm' => false, 'data-method' => false, // for overide yii data api
                                'method' => 'post',
                            ]]);
                    },
                ],
                'updateOptions' => ['title' => 'Aktualizacja', 'data-toggle' => 'tooltip'],
                'vAlign' => 'middle',
            ]
        ];
    }

    /**
     * List of transaction categories. Depends of argument(true,false),
     * return only main categories or categories with subcategories
     *
     * @param bool $withSubcategories
     * @return array
     */
    public static function getCategories($withSubcategories = true)
    {
        $categoryList = [];

        $mainCategories = Category::find()->where(['parent' => 0])->all();
        if ($withSubcategories === false) {
            return ArrayHelper::map($mainCategories, 'id', 'name');
        }

        foreach ($mainCategories as $mainCategory) {
            $subCategories = Category::find()->where(['parent' => $mainCategory->id])->all();
            foreach ($subCategories as $subCategory) {
                $categoryList[$mainCategory->name][$subCategory->id] = $subCategory->name;
            }

        }
        return $categoryList;
    }

    /**
     * Return array with all word categories
     * As a key assigned is word category, as a value assigned is category id
     *
     * @return array
     */
    public static function getWordCategories()
    {
        $models = Category::find()->where(['IS NOT', 'word_category', null])->all();
        $wordsCategories = [];

        foreach ($models as $model) {
            $wordCategories = explode(',', $model->word_category);
            $wordsCategories = array_merge($wordsCategories, array_fill_keys($wordCategories, $model->id));
        }

        return $wordsCategories;
    }
}