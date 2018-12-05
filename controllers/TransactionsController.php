<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use app\models\Transactions;
use app\models\CsvImporter;

class TransactionsController extends Controller
{
    public function actionIndex()
    {
        $model = new Transactions();

        if ($model->load(Yii::$app->request->post())) {
            $executionStartTime = microtime(true);

            $importer = new CsvImporter($_FILES['Transactions']['tmp_name']['file'], false, ';');
            $data = $importer->get();
            $insertData = $model->migrateToBase($data);

            $executionEndTime = microtime(true);
            $seconds = round($executionEndTime - $executionStartTime, '2');

            Yii::$app->session->setFlash('success', "Dane zostały zaimportowane prawidłowo. Wykonanie skryptu zajęło {$seconds} sek. <br> Ilość dodanych rekordów: <strong>{$insertData}</strong>");
            return $this->redirect(Yii::$app->request->baseUrl . '/index.php' . '/transactions/index');
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Transactions::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}