<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\tinymce\TinyMce;
use kartik\widgets\FileInput;
/* @var $this yii\web\View */
/* @var $model common\models\PhanTich */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="box box-primary">

    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>
        <div class="col-sm-6">
            <?= $form->field($model, 'Title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'Description')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'Content')->widget(TinyMce::className(), [
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

        </div>

        <div class="col-sm-6">

            <?= $form->field($model, 'Type')->dropDownList(\common\models\PhanTich::MIEN_ARRAY()) ?>

            <?= $form->field($model, 'Thumbnail')->widget(FileInput::classname(), [
                'options' => ['accept' => 'image/*'],
            ]);?>

            <hr>
            <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-block']) ?>
        </div>


        <?php ActiveForm::end(); ?>
    </div>
</div>
