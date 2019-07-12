<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use  kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $model common\models\query\ResultQuery */
/* @var $form yii\bootstrap\ActiveForm */
?>
<style>

</style>
<div class="result-search">

    <?php $form = ActiveForm::begin([
        'action' => ['ket-qua-xo-so'],
        'method' => 'get',
    ]); ?>

    <div class="col-sm-12 form-search" style="display: flex;">
        <?php $para = Yii::$app->getRequest()->getQueryParam('inputKi') ?>
        <strong style="line-height: 30px;">Từ ngày - đến ngày:</strong>
        <?php
        echo '<div class="input-group drp-container">';
        echo DateRangePicker::widget([
                'name'=>'dateToDate',
                'value' => $para,
                'useWithAddon'=>true,
                'convertFormat'=>true,
                'startAttribute' => 'from_date',
                'endAttribute' => 'to_date',
                'pluginOptions'=>[
                    'locale'=>['format' => 'Y-m-d'],
                ]
            ]) ;
        echo '</div>'; ?>

        <div class="form-group">
            &nbsp;&nbsp;<?php echo Html::submitButton('Cập nhật ', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>