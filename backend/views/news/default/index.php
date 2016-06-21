<?php

use yii\helpers\Html;
use yii\grid\GridView;
use backend\modules\news\models\News;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\News\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '文章列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zwdt-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            //'content:ntext',
            'author',
            //'thumb',
            [
	    		'attribute' => 'status',
	    		'filter' => Html::activeDropDownList($searchModel, 'status', News::getStatus(), ['class' => 'form-control']),
	    		'value' => function ($data) {
		    			return News::getStatus()[$data->status];
		    		},
    		],
            // 'create_at',
            // 'update_at',
            // 'clicks',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
