<?php

namespace app\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transactions;

/**
 * TransactionsSearch represents the model behind the search form of `app\models\Transactions`.
 */
class TransactionsSearch extends Transactions
{
    public function rules()
    {
        return [
            [['id', 'id_user'], 'integer'],
            [['date', 'name_sender', 'name_recipient', 'transaction_detail', 'amount', 'created_at'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Transactions::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_user' => $this->id_user,
            'date' => $this->date,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name_sender', $this->name_sender])
            ->andFilterWhere(['like', 'name_recipient', $this->name_recipient])
            ->andFilterWhere(['like', 'transaction_detail', $this->transaction_detail])
            ->andFilterWhere(['like', 'amount', $this->amount]);

        return $dataProvider;
    }
}