<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html;

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

    /**
     * Save transactions do database, return number od added records
     *
     * @param $data array
     * @return int
     */
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

    /**
     * Columns to finances dynagrid
     *
     * @return array
     */
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

                    return '<div class="' . $class . '">' . Yii::$app->formatter->asDecimal($model->amount) . ' zł</div>';
                }
            ],
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
                        return Html::a('<span class="fa fa-edit" style="color:#222d32;"></span>', false, ['value' => Url::to(['update', 'id' => $model->id]), 'class' => 'loadAjaxContent', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Aktualizuj transakcje']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="fas fa-trash-alt" style="color:#222d32;"></span>', $url, ['data-pjax' => 0, 'title' => 'Usuń',
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
     * Get user
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'id_user']);
    }
}