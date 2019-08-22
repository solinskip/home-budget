<?

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\editable\Editable;
use kartik\grid\GridView;

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
    public $dateRangeStart;
    public $dateRangeEnd;

    public static function tableName()
    {
        return '{{%transactions}}';
    }

    public function rules()
    {
        return [
            [['id_user', 'category_id'], 'integer'],
            [['date', 'transaction_detail', 'amount'], 'required'],
            [['date'], 'safe'],
            [['amount'], 'number'],
            [['name_sender', 'name_recipient', 'transaction_detail'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'id']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
            [['file'], 'file'],

            [['category_id'], 'required', 'message' => '{attribute} nie może pozostać bez wartości', 'on' => 'bulk-assign'],
            [['file'], 'required', 'on' => 'upload'],
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
        $wordCategories = Category::getWordCategories();

        foreach ($data as $item) {
            if ($i >= 2) {
                $date = explode('-', $item[0]);
                strpos($item[4], 'BLIK') ? $transactionDetail = 'Płatność BLIK' : $transactionDetail = $item[4];

                $query = Transactions::find()->where([
                    'id_user' => $userId,
                    'date' => $date[2] . '-' . $date[1] . '-' . $date[0],
                    'name_sender' => $item[2],
                    'name_recipient' => $item[3],
                    'transaction_detail' => $transactionDetail,
                    'amount' => $item[5],
                ])->one();

                if (empty($query)) {
                    $model = new Transactions();
                    $model->id_user = $userId;
                    $model->category_id = $this->in_array_stripos($item[4], $wordCategories);
                    $model->date = $date[2] . '-' . $date[1] . '-' . $date[0];
                    $model->name_sender = $item[2];
                    $model->name_recipient = $item[3];
                    $model->transaction_detail = $transactionDetail;
                    $model->amount = $item[5];
                    $model->save();
                    $insertData++;
                }
            }
            $i++;
        }

        //set new update date
        $user = User::findByUsername(Yii::$app->user->identity->username);
        $user->last_upload = time();
        $user->save();

        return $insertData;
    }

    /**
     * Checking if in sentence occurs word from array
     *
     * @param $word
     * @param $array
     * @return array|null
     */
    public function in_array_stripos($word, $array)
    {
        foreach ($array as $key => $value) {
            if (is_int(stripos($word, $key))) {
                return $value;
            }
        }
        return null;
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
                ]),
                'vAlign' => 'middle',
                'checkboxOptions' => function () {
                    return [
                        'class' => 'transactions-checkbox',
                    ];
                },
            ],
            [
                'attribute' => 'date',
                'filterType' => GridView::FILTER_DATE_RANGE,
                'filterWidgetOptions' => ([
                    'startAttribute' => 'dateRangeStart',
                    'endAttribute' => 'dateRangeEnd',
                    'presetDropdown' => true,
                    'pluginOptions' => [
                        'opens' => 'right',
                        'locale' => [
                            'format' => 'Y-MM-DD',
                        ],
                    ],
                ]),
                'vAlign' => 'middle',
                'format' => 'raw',
                'contentOptions' => ['style' => 'text-align:center'],
            ],
            [
                'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'category_id',
                'value' => 'category.name',
                'visible' => isset(Yii::$app->request->get()['category']) ? false : true,
                'width' => '160px',
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
                'urlCreator' => function ($action, $model, $key) {
                    return Url::to([$action, 'id' => $key]);
                },
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="fa fa-edit" style="color:#222d32;"></span>', false, ['value' => $url, 'class' => 'loadAjaxContent', 'style' => 'cursor: pointer', 'icon' => '<i class="fa fa-tasks"></i>', 'modaltitle' => 'Aktualizuj transakcje']);
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
     * Total expenses divided into categories or subcategories depending on the category argument
     * Return positive or negative amount depending on the positive argument
     * Category type:
     *      0 = main category
     *      0 < subcategories
     *
     * @param $category integer
     * @param bool $positive
     * @return float
     */
    public function expensesCategories($category, $positive = true)
    {
        $modelCategory = Category::findOne($category);

        if ($modelCategory->parent === 0) {
            $subcategoriesIds = [];
            $modelSubcategories = Category::find()->select('id')->where(['parent' => $category])->all();

            foreach ($modelSubcategories as $modelSubcategory) {
                array_push($subcategoriesIds, $modelSubcategory->id);
            }

            $category = $subcategoriesIds;
        }

        $dateRange = self::getDateRange();

        $amount = round(Transactions::find()
            ->where(['between', 'date', $dateRange['from'], $dateRange['to']])
            ->andWhere(['category_id' => $category])
            ->andWhere(['id_user' => Yii::$app->user->id])
            ->sum('amount'), 2);

        return $positive === true ? abs($amount) : $amount;
    }

    /**
     * Get date range
     * If date range isset in URL then use them
     * else set start day on fist day of month and end day on current day
     *
     * @return array
     */
    public function getDateRange()
    {
        if (!empty($dateRange = Yii::$app->request->get())) {
            $from = date('Y-m-d',strtotime($dateRange['from']));
            $to = date('Y-m-d', strtotime($dateRange['to']));
        } else {
            $from = date('Y-m-01');
            $to = date('Y-m-d');
        }

        return ['from' => $from, 'to' => $to];
    }

    /**
     * Daily expenses current logged user
     * If daily expenses equals > 0 return total expenses, else return 0
     *
     * @param $date string(Y-m-d)
     * @return int|mixed
     */
    public function dailyExpenses($date)
    {
        $dailyExpenses = Transactions::find()
            ->where(['id_user' => Yii::$app->user->id])
            ->andWhere(['date' => $date])
            ->sum('amount');

        return $dailyExpenses ? $dailyExpenses * (-1) : 0;
    }

    /**
     * Monthly expenses actual user
     *
     * @return float|int
     */
    public function monthlyExpenses()
    {
        $dateRange = self::getDateRange();

        return abs(round(Transactions::find()
                ->where(['id_user' => Yii::$app->user->id])
                ->andWhere(['between', 'date', $dateRange['from'], $dateRange['to']])
                ->andWhere(['<', 'amount', 0])
                ->sum('amount'), 2)
        );
    }

    /**
     * Monthly expenses divided into days
     *
     * @return array
     */
    public function monthlyExpansesPerDay()
    {
        $date = [];
        $balance = [];
        $dateRange = self::getDateRange();

        for ($currentDay = $dateRange['from']; $currentDay <= $dateRange['to']; $currentDay = date('Y-m-d', strtotime('+1 day', strtotime($currentDay)))) {
            array_push($balance, (self::dailyExpenses(date($currentDay)) + end($balance)));
            array_push($date, date('m/d', strtotime($currentDay)));
        }

        return ['date' => $date, 'balance' => $balance];
    }

    /**
     * Get user
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }
}