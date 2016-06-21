<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\zwdt\models\Zwdt;

/* @var $this yii\web\View */
/* @var $model backend\modules\news\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '政务动态', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zwdt-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '确定要删除吗?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
    		
           /*  [                      
	            'label' => '封面图片',
	            'value' => '/'.$model->thumb,
	            'format' => ['image',['width'=>'150px','height'=>'150px']],
    			//'php:d-M-Y H:i:s'
	        ], */
            'content:raw',
            'author',
            [
	    		'attribute' => 'status',
	    		'value' => News::getStatus()[$model->status],
    		],
            'create_at:datetime',
            'update_at:datetime',
            'clicks',
        ],
    ]) ?>

</div>
