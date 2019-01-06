<?php

namespace app\models;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

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

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
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
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'category',
                'label' => 'Kategoria',
                'value' => function ($model) {
                    return $model->parent === 0 ? $model->name : '';
                }
            ],
            [
                'attribute' => 'subcategory',
                'label' => 'Podkategoria',
                'value' => function ($model) {
                    return $model->parent !== 0 ? $model->name : '';
                }
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
    public function getCategories($withSubcategories = true)
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
}