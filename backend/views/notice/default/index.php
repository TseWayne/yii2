<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\notice\models\Notice;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\notice\models\NoticeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '公告列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
    	'options' => ['class' => 'grid-view','style'=>'overflow:auto', 'id' => 'grid'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
    		/*[
    		 'class' => 'yii\grid\CheckboxColumn',
    		'name' => 'id', 
    		],*/
            'id',
            'title',
            //'content:ntext',
    		[
    			'label' => '采购项目',
	    		'attribute' => 'shixiang',
	    		'value' => 'procurement.shixiang',
    			'filter'=> Html::activeTextInput($searchModel, 'shixiang',['class'=>'form-control']) 
    		],
    		'author',
            [
	    		'attribute' => 'status',
	    		'filter' => Html::activeDropDownList($searchModel, 'status', Notice::getStatus(), ['class' => 'form-control']),
	    		'value' => function ($data) {
		    			return Notice::getStatus()[$data->status];
		    		},
    		],
            // 'create_at',
            // 'update_at',
            // 'clicks',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
<!-- <?//= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-success gridview']) ?>
<?php
	/* $this->registerJs('
		$(".gridview").on("click", function () {
			//注意这里的$("#grid")，要跟我们第一步设定的options id一致
			var keys = $("#grid").yiiGridView("getSelectedRows");
			console.log(keys);
		});
	'); */
?>-->
