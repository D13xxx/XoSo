<?php
/**
 * Created by nvdiepse.
 * User: NgoGiaDiep
 * Date: 6/25/2019
 * Time: 4:14 PM
 */
use kartik\grid\GridView;
$this->title = 'Mega645';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <br>
        <?php echo $this->render('_search-mega645', [
        ]) ?>
        <br>
    </div><!-- /.box-header -->
    <div class="box-body">

        <div class="col-sm-12">
            <?php echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'ki',
                    [
                        'attribute' => 'ngay',
                        'options' => ['style' => 'width: 15%'],
                        'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Năm-Tháng-Ngày'
                        ],
                        'value' => function ($model) {
                            return date('d/m/Y',strtotime($model->ngay));
                        },
                    ],
                    'jackport',
                    'so1',
                    'so2',
                    'so3',
                    'so4',
                    'so5',
                    'so6',
                    'g0',
                    'g1',
                    'g2',
                    'g3',
                ],
            ]); ?>
        </div><!-- /.box-body -->
    </div>
</div>
