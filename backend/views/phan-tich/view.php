<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PhanTich;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\PhanTich */

$this->title = $model->Title;
$this->params['breadcrumbs'][] = ['label' => 'Phan Tiches', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="box box-primary">


    <div class="box-body">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'Title',
                'Description',
                [
                    'attribute'=>'Thumbnail',
                    'format'=>'raw',
                    'value'=>function($data){
                        if($data->Thumbnail==''||$data->Thumbnail==null){
                            return Html::img(Yii::getAlias('@web').'/images/phan-tich/download.png',[
                                'style'=>['width'=>'140px']
                            ]);
                        } else {
                            return Html::img(Yii::getAlias('@web').'/images/phan-tich/'.$data->Thumbnail,[
                                'style'=>['width'=>'140px']
                            ]);
                        }
                    }
                ],
                [
                    'attribute'=>'Content',
                    'format'=>'raw',
                ],
                [
                    'attribute'=>'Type',
                    'format'=>'html',
                    'value'=>function($data)
                    {
                        return '<span class="label label-'.PhanTich::MIEN_COLOR_ARRAY()[(int)$data->Type].'">'.PhanTich::MIEN_ARRAY()[(int)$data->Type].'</span>';
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => PhanTich::MIEN_ARRAY(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => ''],
                ],
                [
                    'attribute'=>'Date',
                    'value'=>function($data){
                        return date('d/m/Y',strtotime($data->Date));
                    }
                ],[
                    'attribute'=>'CreateDate',
                    'value'=>function($data){
                        return date('d/m/Y',strtotime($data->CreateDate));
                    }
                ],[
                    'attribute'=>'UpdateDate',
                    'value'=>function($data){
                        if(!empty($data->UpdateDate)){
                            return date('d/m/Y',strtotime($data->UpdateDate));
                        }
                    }
                ],

            ],
        ]) ?>

    </div>
    <div class="box-header with-border">
        <?= \yii\helpers\Html::a('Quay lại',['index'],['class'=>'btn btn-default','title'=>'Quay lại']) ?>
        <br>
    </div><!-- /.box-header -->
</div>
