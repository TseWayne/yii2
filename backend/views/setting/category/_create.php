<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-create">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name') ?>
    
    <div class="form-group">
        <?php 
        if(!$model->isNewRecord){
            echo Html::a('<span>目录</span>',['index','pid'=>$pid],['class' => 'btn btn-success']);
        }
        ?>
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
