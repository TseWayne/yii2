<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Procurement */
/* @var $form yii\widgets\ActiveForm */


?>

<div class="procurement-signform">
    

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'sis')->radioList([1=>'同意',0=>'不同意'])->label('');?>
    <?= $form->field($model, 'smemo')->textarea(['rows' => 4])->label('意见内容(驳回审核的时候，请填写详细的驳回理由。)');?>
    
 
    <div class="form-group">
        <?= Html::submitButton('签署意见', ['class' => 'btn btn-primary']) ?>
    </div>

    <p class='text-right'><?=date('Y年m月d日')?></p>
    <?php ActiveForm::end(); ?>
   

</div>
