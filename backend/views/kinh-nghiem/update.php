<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KinhNghiem */

$this->title = 'Cập nhật: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Kinh nghiệm', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Cập nhật';
?>
<div class="kinh-nghiem-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
