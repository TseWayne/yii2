<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use funson86\setting\models\Category;


/* @var $this yii\web\View */
/* @var $model common\models\PostMeta */

$this->title = '抽取专家';
$this->params['breadcrumbs'][] = ['label' => '项目ID:'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;


$postUrl = Yii::$app->urlManager->createUrl('procurement/expert/do');
$changeUrl = Yii::$app->urlManager->createUrl('procurement/expert/change');
$submitUrl = Yii::$app->urlManager->createUrl('procurement/expert/zjsubmit');
?>
<div id="w0" class="grid-view">
	<h1><?= Html::encode($this->title) ?></h1>

	<?php 
		$form = ActiveForm::begin([
					'id' => 'zhuanjia',
					'action' => $submitUrl,
				]);
	?>
		<table class="table table-striped table-bordered">
			<tbody>
			
				<tr>
					<th width="110">申报单位:</th>
					<td><?= Category::findOne(['id'=>$model->danwei])->name; ?></td>
				</tr>
				<tr data-key="1">
					<th width="110">申报事项:</th>
					<td><?= nl2br($model->shixiang); ?></td>
				</tr>
				<tr>
					<th width="110">
						<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">添加专家</button>
						
					</th>
					<td id="msgBox">
						
					</td>
				</tr>
				
				<tr>
					<td colspan="2">
						<div class="form-group">
							<input type="hidden" name="cgId" value="<?= $id ?>" />
					        <div class="col-lg-offset-1 col-lg-11">
					            <button type="button" class="btn btn-success" id="zjSubmit">提交</button>
					        </div>
					    </div>
					</td>
				</tr>
				
			</tbody>
		</table>
	<?php ActiveForm::end() ?>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">添加专家</h4>
      </div>
      <div class="modal-body">
          <div class="form-group">
            <label for="recipient-name" class="control-label">选择类别:</label>
				<?= 
					 Html::dropDownList('type2', null, $arr[2], [
					 	'prompt'=>'点击选择',
					 	'class' => 'form-control',
					 	'id'=>'type2'
					 ]);
			 	?>
			 
			
            <label for="recipient-name" class="control-label">选择人数:</label>
            <input type="text" class="form-control" id="num" value="3">
          </div>
          <input type="hidden" id="postUrl" value="<?= $postUrl ?>" />
          <input type="hidden" id="changeUrl" value="<?= $changeUrl ?>" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default"  id="chouqu" data-dismiss="modal">抽取</button>
      </div>
    </div>
  </div>
</div>
<?php $this->registerJsFile('/zhiyii/backend/web/js/expert.js',['depends'=>['backend\assets\AppAsset'], 'position'=> $this::POS_END]);?>