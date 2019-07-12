<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PhanTich */

$this->title = 'Cập nhật:' . $model->Title;
$this->params['breadcrumbs'][] = ['label' => 'Phân tích', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="phan-tich-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
