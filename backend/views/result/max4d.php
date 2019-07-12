<?php
/**
 * Created by nvdiepse.
 * User: NgoGiaDiep
 * Date: 6/25/2019
 * Time: 4:14 PM
 */
use kartik\grid\GridView;
$this->title = 'Max4d';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-primary">
    <div class="box-header with-border">
        <br>
        <?= $this->render('_search-max4d') ?>
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
                    'g1',
                    'g21',
                    'g22',
                    'g31',
                    'g32',
                    'g33',
                    'g4',
                    'g5',
                    't1',
                    't2',
                    't3',
                    't4',
                    't5',
                    'th41',
                    'th42',
                    'th43',
                    'th61',
                    'th62',
                    'th63',
                    'th121',
                    'th122',
                    'th123',
                    'th241',
                    'th242',
                    'th243',
                ],
            ]); ?>
        </div><!-- /.box-body -->
    </div>
</div>
