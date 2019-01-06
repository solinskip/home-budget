<?php

namespace app\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Category;

/**
 * CategorySearch represents the model behind the search form of `app\models\Category`.
 */
class CategorySearch extends Category
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'parent'], 'integer'],
            [['category', 'subcategory', 'name', 'description', 'word_category'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * Return categories with proper order, first category then its subcategories. Example:
     * Food
     *      Restaurant
     *      Grocery
     *      Alcohol
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        //get all subcategories without main categories
        $categories = Category::find()->select(['parent'])->where(['<>', 'parent', '0'])->groupBy('parent')->asArray()->all();

        //create query for first category with subcategories
        $query = Category::find()->where(['or', ['id' => $categories['0']['parent']], ['parent' => $categories['0']['parent']]])->orderBy('parent, name');

        //union categories with proper sorting, first category then its subcategories
        foreach ($categories as $category) {
            $query1 = Category::find()->where(['or', ['id' => $category['parent']], ['parent' => $category['parent']]])->orderBy('parent, name');
            $query = $query->union($query1);
            unset($categories['0']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['category'] = [
            'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC]
        ];

        $dataProvider->sort->attributes['subcategory'] = [
            'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC]
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'parent' => $this->parent,
        ]);

        $query->andFilterWhere(['like', 'name', $this->category])
            ->andFilterWhere(['like', 'name', $this->subcategory])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'word_category', $this->word_category]);
        return $dataProvider;
    }
}