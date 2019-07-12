<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\KinhNghiem;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\PhanTich */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Kinh nghiệm', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="box box-primary">


    <div class="box-body">

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'title',
                'description',
                [
                    'attribute'=>'thumbnail',
                    'format'=>'raw',
                    'value'=>function($data){
                        if($data->thumbnail==''||$data->thumbnail==null){
                            return Html::img(Yii::getAlias('@web').'/images/kinh-nghiem/download.png',[
                                'style'=>['width'=>'140px']
                            ]);
                        } else {
                            return Html::img(Yii::getAlias('@web').'/images/kinh-nghiem/'.$data->thumbnail,[
                                'style'=>['width'=>'140px']
                            ]);
                        }
                    }
                ],
                [
                    'attribute'=>'content',
                    'format'=>'raw',
                ],
                [
                    'attribute'=>'public',
                    'format'=>'html',
                    'value'=>function($data)
                    {
                        return '<span class="label label-'.KinhNghiem::KN_COLOR_ARRAY()[(int)$data->public].'">'.KinhNghiem::KN_ARRAY()[(int)$data->public].'</span>';
                    },
                    'filterType' => GridView::FILTER_SELECT2,
                    'filter' => KinhNghiem::KN_ARRAY(),
                    'filterWidgetOptions' => [
                        'pluginOptions' => ['allowClear' => true],
                    ],
                    'filterInputOptions' => ['placeholder' => ''],
                ],
                'order_number',

            ],
        ]) ?>

    </div>
    <div class="box-header with-border">
        <?= \yii\helpers\Html::a('Quay lại',['index'],['class'=>'btn btn-default','title'=>'Quay lại']) ?>
        <br>
    </div><!-- /.box-header -->
</div>

<?php
