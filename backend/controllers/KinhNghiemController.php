<?php

namespace backend\controllers;

use Yii;
use common\models\KinhNghiem;
use common\models\query\KinhNghiemQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
/**
 * KinhNghiemController implements the CRUD actions for KinhNghiem model.
 */
class KinhNghiemController extends Controller
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
     * Lists all KinhNghiem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new KinhNghiemQuery();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single KinhNghiem model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new KinhNghiem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new KinhNghiem();

        if ($model->load(Yii::$app->request->post())) {
            $thumbnail=UploadedFile::getInstance($model,'thumbnail');
            if(!is_null($thumbnail)){
                $temp=$thumbnail->name;
                $path =Yii::$app->basePath.'/web/images/kinh-nghiem/'.$temp;
                $model->thumbnail=$temp;
                $thumbnail->saveAs($path);
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing KinhNghiem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!is_null($model->thumbnail)){
            $img=$model->thumbnail;
        }
        if ($model->load(Yii::$app->request->post())) {
            $thumbnail=UploadedFile::getInstance($model,'thumbnail');
            if(!is_null($thumbnail)){
                $temp=$thumbnail->name;
                $path =Yii::$app->basePath.'/web/images/kinh-nghiem/'.$temp;
                $model->thumbnail=$temp;
                $thumbnail->saveAs($path);
            } else {
                $model->thumbnail=$img;
            }
            if ($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    /**
     * Deletes an existing KinhNghiem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the KinhNghiem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KinhNghiem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KinhNghiem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
