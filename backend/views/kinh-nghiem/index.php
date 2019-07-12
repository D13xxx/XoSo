
<?php
/**
 * Created by nvdiepse.
 * User: NgoGiaDiep
 * Date: 6/25/2019
 * Time: 4:14 PM
 */
use kartik\grid\GridView;
use yii\helpers\Html;
use common\models\KinhNghiem;
$this->title = 'Kinh nghiệm';
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
                'title',
                'order_number',
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
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?>
    </div>
</div>
