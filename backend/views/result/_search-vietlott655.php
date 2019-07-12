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
<div class="result-search col-sm-12">
    <?php $para = Yii::$app->getRequest()->getQueryParam('inputKi') ?>
    <form action="vietlott655" method="get"  class="form-inline">
        <strong>Nhập kì tìm kiếm : </strong>
        <input class="form-control form-control-sm mr-3 w-75" name="inputKi" type="text"  value="<?= $para?>"  placeholder="Ví dụ : 0xxxx" aria-label="Search">
        <button type="submit" class="btn btn-primary" > <i class="glyphicon glyphicon-search"></i> Cập nhật</button>
    </form>
</div>
