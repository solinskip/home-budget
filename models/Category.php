<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property string $description
 */
class Category extends \yii\db\ActiveRecord
{
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
            [['name', 'parent', 'description'], 'required'],
            [['parent'], 'integer'],
            [['name', 'description'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'parent' => 'Parent',
            'description' => 'Description',
        ];
    }

    /**
     * List of transaction categories
     *
     * @return array
     */
    public function getCategories()
    {
        $categoryList = [];

        $mainCategories = Category::find()->where(['parent' => 0])->all();
        foreach ($mainCategories as $mainCategory) {
            $subCategories = Category::find()->where(['parent' => $mainCategory->id])->all();
            foreach ($subCategories as $subCategory) {
                $categoryList[$mainCategory->name][$subCategory->id] = $subCategory->name;
            }

        }
        return $categoryList;
    }
}