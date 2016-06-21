<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\notice\models\Notice;

/* @var $this yii\web\View */
/* @var $model backend\modules\notice\models\Notice */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '公告列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定呀删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'content:raw',
            [
	    		'attribute' => 'cgId',
	    		'value' => Notice::get_cg_text($model->cgId),
    		],
            [
	    		'attribute' => 'status',
	    		'value' => Notice::getStatus()[$model->status],
    		],
    		'create_at:date',
    		'update_at:date',
           /*  [
	    		'attribute' => 'create_at',
	    		'value' => date('Y-m-d H:m:s',$model->create_at),
    		],    		
    		[
    		'attribute' => 'update_at',
    		'value' => date('Y-m-d H:m:s',$model->update_at),
    		],*/ 
            'clicks',
        ],
    ]) ?>

</div>
