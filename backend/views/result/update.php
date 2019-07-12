<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Result */

$this->title = 'Update Result: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="result-update">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
