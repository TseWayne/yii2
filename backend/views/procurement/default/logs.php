<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\procurement\models\ActionLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '操作记录';
$this->params['breadcrumbs'][] = $this->title;

$col = [
    ['class' => 'yii\grid\SerialColumn'],
    'memo:ntext',
    'ip',
];

if(\Yii::$app->user->can("日志管理"))
    $col[] = ['attribute' => 'username','label'=>'用户名', 'value'=>function($model){
        return User::findOne($model->userid)->username;
    }];
    
$col[] = 'time:datetime';
?>


<div class="action-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $col,
    ]); ?>
    
</div>
