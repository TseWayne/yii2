<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\news\models\news */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="zwdt-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'content')->widget('kucha\ueditor\UEditor',[
    		'clientOptions' => [
	    		//编辑区域大小
	    		'initialFrameHeight' => '430',
	    		//定制菜单
	    		'toolbars' => [
		    		[
			    		'fullscreen', 'source', 'undo', 'redo', '|',
			    		'fontsize',
			    		'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
			    		'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
			    		'forecolor', 'backcolor', '|',
			    		'lineheight', '|',
			    		'indent', '|',
    					'simpleupload','insertimage','|',
		    		],
    			]
    		]
    ]); ?>

  	<!-- <?//= $form->field($model, 'author')->textInput(['maxlength' => true]) ?> -->

   <!-- <?//= $form->field($model, 'thumb')->textInput(['maxlength' => true]) ?>-->

    <?= $form->field($model, 'status')->dropDownList(["0"=>"未审核","1"=>"已审核"]) ?>

    <!--<?//= $form->field($model, 'create_at')->textInput() ?>-->

   <!-- <?//= $form->field($model, 'update_at')->textInput() ?>-->

    <?= $form->field($model, 'clicks')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
