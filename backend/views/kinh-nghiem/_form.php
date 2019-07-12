<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use kartik\widgets\FileInput;
use kartik\widgets\SwitchInput;
/* @var $this yii\web\View */
/* @var $model common\models\KinhNghiem */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">

    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-sm-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'content')->widget(TinyMce::className(), [
                'options' => ['rows' => 6],
                'language' => 'es',
                'clientOptions' => [
                    'plugins' => [
                        "advlist autolink lists link charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
                ]
            ]);?>

            <?= $form->field($model, 'public')->dropDownList(\common\models\KinhNghiem::KN_ARRAY()) ?>

        </div>

        <div class="col-sm-6">

            <?= $form->field($model, 'order_number')->textInput() ?>

            <?= $form->field($model, 'thumbnail')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
            ]);?>

            <hr>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-block']) ?>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
