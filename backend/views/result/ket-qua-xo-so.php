<?php
/**
 * Created by nvdiepse.
 * User: NgoGiaDiep
 * Date: 6/25/2019
 * Time: 4:14 PM
 */
use kartik\grid\GridView;
$this->title = 'Xổ số cả nước';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary">
    <div class="box-header with-border">
        <?= $this->render('_search-ket-qua-xo-so') ?>
    </div><!-- /.box-header -->
    <div class="box-body">
            <?php echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'postdate',
                        'options' => ['style' => 'width: 25%'],
                        'filterInputOptions' => [
                            'class'       => 'form-control',
                            'placeholder' => 'Năm-Tháng-Ngày'
                        ],
                        'value' => function ($model) {
                            return date('d/m/Y',strtotime($model->postdate));
                        },
                    ],
                    [
                        'attribute' => 'result',
                        'format'=>'raw',
                    ],
                    [
                        'attribute' => 'province',
                        'value' => function ($model) {
                            return $model->province ? $model->provinces->category : '';
                        },
                        'options' => ['style' => 'width: 15%'],
                        'filterType' => GridView::FILTER_SELECT2,
                        'filter' => \yii\helpers\ArrayHelper::map(\common\models\Province::find()->all(), 'id', 'category'),
                        'filterWidgetOptions' => [
                            'pluginOptions' => ['allowClear' => true],
                        ],
                        'filterInputOptions' => ['placeholder' => ''],
                        'format' => 'raw'
                    ],
                ],
            ]); ?>
</div>
