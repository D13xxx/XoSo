<?php

namespace backend\controllers;
use yii\web\UploadedFile;
use Yii;
use common\models\PhanTich;
use common\models\query\PhanTichQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PhanTichController implements the CRUD actions for PhanTich model.
 */
class PhanTichController extends Controller
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
     * Lists all PhanTich models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PhanTichQuery();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PhanTich model.
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
     * Creates a new PhanTich model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PhanTich();

        if ($model->load(Yii::$app->request->post())) {
            $model->CreateDate = date('Y-m-d');
            $model->Date = date('Y-m-d');
            $thumbnail=UploadedFile::getInstance($model,'Thumbnail');
            if(!is_null($thumbnail)){
                $temp=$thumbnail->name;
                $path =Yii::$app->basePath.'/web/images/phan-tich/'.$temp;
                $model->Thumbnail=$temp;
                $thumbnail->saveAs($path);
            }
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->ID]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing PhanTich model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $img=$model->Thumbnail;
        if ($model->load(Yii::$app->request->post())) {
            $thumbnail=UploadedFile::getInstance($model,'Thumbnail');
            if(!is_null($thumbnail)){
                $temp=$thumbnail->name;
                $path =Yii::$app->basePath.'/web/images/phan-tich/'.$temp;
                $model->Thumbnail=$temp;
                $thumbnail->saveAs($path);
            } else {
                $model->Thumbnail=$img;
            }
            if ($model->save()){
                return $this->redirect(['view', 'id' => $model->ID]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing PhanTich model.
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
     * Finds the PhanTich model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PhanTich the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PhanTich::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
