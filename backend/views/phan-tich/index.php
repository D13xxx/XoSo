<?php
/**
 * Created by nvdiepse.
 * User: NgoGiaDiep
 * Date: 6/25/2019
 * Time: 4:14 PM
 */
use kartik\grid\GridView;
use yii\helpers\Html;
use common\models\PhanTich;
$this->title = 'Phân tích';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary">
    <div class="box-header with-border">
        <?= \yii\helpers\Html::a('Thêm mới',['create'],['class'=>'btn btn-success','title'=>'Thêm mới']) ?>
        <br>
    </div><!-- /.box-header -->

    <div class="box-body">
        <?php echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'Title',
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

                'Date',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>