<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\editable\Editable;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $id_user
 * @property int $category_id
 * @property string $date
 * @property string $name_sender
 * @property string $name_recipient
 * @property string $transaction_detail
 * @property double $amount
 *
 * @property Category $category
 * @property User $user
 */
class Transactions extends ActiveRecord
{
    //virtual variables
    public $file;
    public $transactionsId;

    public static function tableName()
    {
        return '{{%transactions}}';
    }

    public function rules()
    {
        return [
            [['id_user', 'category_id'], 'integer'],
            [['category_id'], 'required', 'message' => '{attribute} nie może pozostać bez wartości', 'on' => 'bulk-assign'],
            [['date', 'transaction_detail', 'amount'], 'required'],
            [['date'], 'safe'],
            [['amount'], 'number'],
            [['name_sender', 'name_recipient', 'transaction_detail'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['id_user' => 'id']],
            [['file'], 'file'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'ID użytkownika',
            'category_id' => 'Kategoria',
            'date' => 'Data transakcji',
            'name_sender' => 'Nazwa nadawcy',
            'name_recipient' => 'Nazwa odbiorcy',
            'transaction_detail' => 'Szczegóły transakcji',
            'amount' => 'Kwota transakcji',
            'file' => 'Plik',
        ];
    }

    /**
     * Save transactions do database, return number of added records
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
     * Columns to transactions dynagrid
     *
     * @return array
     */
    public function getColumns()
    {
        return [
            [
                'class' => 'kartik\grid\CheckboxColumn',
                'width' => '20px',
                'header' => Html::checkBox('selection_all', false, [
                    'class' => 'select-on-check-all',
                    'label' => '*',
                ]),
                'vAlign' => 'top',
                'checkboxOptions' => function () {
                    return [
                        'class' => 'transactions-checkbox',
                    ];
                },
            ],
            ['class' => 'yii\grid\SerialColumn'],
            'date',
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'category_id',
                'value' => 'category.name',
                'visible' => isset(Yii::$app->request->get()['category']) ? false : true,
                'width' => '300px',
                'readonly' => isset(Yii::$app->request->get()['_toga9a4094b']) ? true : false,
                'label' => 'Kategoria',
                'editableOptions' => function () {
                    return [
                        'header' => 'kategorie',
                        'size' => 'md',
                        'submitButton' => ['icon' => '<i class="fas fa-check"></i>', 'class' => 'btn btn-primary', 'style' => 'margin-left: 5px; padding: 0 5px 0 5px; font-size: 15px'],
                        'resetButton' => ['icon' => '<i class="fas fa-ban"></i>', 'class' => 'btn btn-danger', 'style' => 'margin-left: 10px; padding: 0 5px 0 5px; font-size: 15px'],
                        'inputType' => Editable::INPUT_WIDGET,
                        'formOptions' => ['action' => [Url::to('transactions/update')]],
                        'widgetClass' => '\kartik\widgets\Select2',
                        'options' => [
                            'data' => Category::getCategories(),
                            'options' => [
                                'placeholder' => 'Wybierz kategorie...',
                            ],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ],
                    ];
                }
            ],
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
                        return Html::a('<span class="fa fa-edit" style="color:#222d32;"></span>', false, ['value' => Url::to(['update', 'id' => $model->id]), 'class' => 'loadAjaxContent', 'style' => 'cursor: pointer', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Aktualizuj transakcje']);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}