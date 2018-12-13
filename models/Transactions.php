<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $id_user
 * @property string $date
 * @property string $name_sender
 * @property string $name_recipient
 * @property string $transaction_detail
 * @property double $amount
 *
 * @property User $user
 */
class Transactions extends ActiveRecord
{
    public $file;

    public static function tableName()
    {
        return '{{%transactions}}';
    }

    public function rules()
    {
        return [
            [['id_user'], 'integer'],
            [['date', 'transaction_detail', 'amount'], 'required'],
            [['date'], 'safe'],
            [['amount'], 'number'],
            [['name_sender', 'name_recipient', 'transaction_detail'], 'string', 'max' => 255],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['file'], 'file'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'ID użytkownika',
            'date' => 'Data transakcji',
            'name_sender' => 'Nazwa nadawcy',
            'name_recipient' => 'Nazwa odbiorcy',
            'transaction_detail' => 'Szczegóły transakcji',
            'amount' => 'Kwota transakcji',
            'file' => 'Plik',
        ];
    }

    public function migrateToBase($data)
    {
        $i = 0;
        $insertData = 0;
        $userId = Yii::$app->user->id;

        foreach ($data as $item) {
            if ($i >= 2) {
                $date = explode('-', $item[0]);
                strpos($item[4], 'BLIK') ? $amount = $item[8] : $amount = $item[5];

                $query = Transactions::find()->where([
                    'id_user' => $userId,
                    'date' => $date[2] . '-' . $date[1] . '-' . $date[0],
                    'name_sender' => $item[2],
                    'name_recipient' => $item[3],
                    'transaction_detail' => $item[4],
                    'amount' => $amount,
                ])->one();

                if (empty($query)) {
                    $model = new Transactions();
                    $model->id_user = Yii::$app->user->id;
                    $model->date = $date[2] . '-' . $date[1] . '-' . $date[0];
                    $model->name_sender = $item[2];
                    $model->name_recipient = $item[3];
                    $model->transaction_detail = $item[4];
                    $model->amount = $amount;
                    $model->save();
                    $insertData++;
                }
            }
            $i++;
        }

        return $insertData;
    }

    public function getColumns()
    {
        return [
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            'name_sender',
            'name_recipient',
            'transaction_detail',
            [
                'attribute' => 'amount',
                'format' => 'raw',
                'vAlign' => 'middle',
                'hAlign' => 'center',
                'value' => function ($model) {
                    $class = $model->amount > 0 ? 'text-success' : 'text-danger';

                    return '<div class="' . $class . '">' . Yii::$app->formatter->asDecimal( $model->amount) . ' zł</div>';
                }
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}