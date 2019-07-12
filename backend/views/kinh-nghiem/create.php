<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\KinhNghiem */

$this->title = 'Thêm mới';
$this->params['breadcrumbs'][] = ['label' => 'Kinh Nghiệm', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="kinh-nghiem-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
