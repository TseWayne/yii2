<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\notice\models\Notice */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="notice-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php if($model->id){ ?>
    <?= $form->field($model, 'cgId')->dropDownList($model->get_cg_id(),['prompt'=>'选择采购项目'])?>
    <?php }else{ ?>
    	<?php if($model->get_cg_id_no_notice()){ ?>
    		<?= $form->field($model, 'cgId')->dropDownList($model->get_cg_id_no_notice(),['prompt'=>'选择采购项目'])?>
    	<?php }else{ ?>
    		<?= $form->field($model, 'cgId')->dropDownList($model->get_cg_id_no_notice(),['prompt'=>'没有可以发布公告采购项目'])?>
    	<?php } ?>
    <?php } ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

	<!--<?//= $form->field($model, 'thumb')->fileInput() ?>  -->

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


    <?= $form->field($model, 'status')->dropDownList(["0"=>"未审核","1"=>"已审核"]) ?>

    <?= $form->field($model, 'clicks')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
