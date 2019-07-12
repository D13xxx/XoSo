<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PhanTich */

$this->title = 'Thêm mới phân tích';
$this->params['breadcrumbs'][] = ['label' => 'Phân tích', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phan-tich-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
