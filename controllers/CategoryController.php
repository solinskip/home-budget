<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\models\Category;
use app\models\search\CategorySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Create category or subcategory depends of argument
     *
     * @param $type string (category or subcategory)
     * @return string|\yii\web\Response
     */
    public function actionCreate($type)
    {
        $model = new Category();
        $type === 'category' ? $model->scenario = 'category' : $model->scenario = 'subcategory';

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->get()['type'] === 'category') {
                $model->name = $model->category;
                $model->parent = '0';
            } else {
                $model->name = $model->subcategory;
                $model->parent = $model->category;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('alert', [
                    'type' => 'success',
                    'title' => 'Informacja',
                    'message' => (Yii::$app->request->get()['type'] === 'category' ? 'Kategoria' : 'Podkategoria') . ' została dodana z powodzeniem',
                    'options' => ['class' => 'alert-success']
                ]);

                return $this->redirect('index');
            }
        }

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }


    /**
     * Updates an existing Category model.
     * Depends of argument type assign value
     *
     * @param $id integer
     * @param $type string(category or subcategory)
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionUpdate($id, $type)
    {
        $model = $this->findModel($id);

        if ($type === 'category') {
            $model->scenario = 'category';
            $model->category = $model->name;
        } else {
            $model->scenario = 'subcategory';
            $model->category = $model->parent;
            $model->subcategory = $model->name;
        }

        if ($model->load(Yii::$app->request->post())) {
            if (Yii::$app->request->get()['type'] === 'category') {
                $model->name = $model->category;
                $model->parent = '0';
            } else {
                $model->name = $model->subcategory;
                $model->parent = $model->category;
            }

            if ($model->update()) {
                Yii::$app->session->setFlash('alert', [
                    'type' => 'success',
                    'title' => 'Informacja',
                    'message' => (Yii::$app->request->get()['type'] === 'category' ? 'Kategoria' : 'Podkategoria') . ' została zaktualizowana z powodzeniem',
                    'options' => ['class' => 'alert-success']
                ]);

                return $this->redirect('index');
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Expand row main category
     *
     * @return string
     */
    public function actionExpandCategory()
    {
        $request = Yii::$app->request;

        //id selected category
        $id = $request->post('expandRowKey');

        if ($id) {
            $searchModel = new CategorySearch();
            $searchModel->parent = $id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->renderAjax('/transactions/_expandCategories', ['dataProvider' => $dataProvider]);
        } else {
            return 'Brak danych do wyświetlenia';
        }
    }

    /**
     * Deletes an existing Category model
     *
     * @param $id integer
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id, $type)
    {
        $model = $this->findModel($id);

        if ($type === 'category') {
            Category::deleteAll(['parent' => $model->id]);
        }

        $model->delete();

        Yii::$app->session->setFlash('alert', [
            'type' => 'success',
            'title' => 'Informacja',
            'message' => (Yii::$app->request->get()['type'] === 'category' ? 'Kategoria wraz z podkategoriami' : 'Podkategoria') . ' została usunięta z powodzeniem',
            'options' => ['class' => 'alert-success']
        ]);

        return $this->redirect(['index']);
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}