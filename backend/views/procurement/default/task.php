<?php

use yii\helpers\Html;
use yii\grid\GridView;
use funson86\setting\models\Category;
use yii\helpers\StringHelper;
use backend\modules\procurement\models\Procurement;
use common\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProcurementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '工作进度';
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
<!--
.table td a{margin-right:5px;}
-->
</style>
<div class="procurement-task">

    <h1><?= Html::encode($this->title) ?></h1>

    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            
            [
                'attribute' => 'info',
                'label' => '项目信息',
                'content' => function($model) {
                    $v = "<dl><dd>修改日期：".date('Y-m-d H:i',$model->edittime)."</dd>
                              <dd>申报单位：".Category::findOne(['id'=>$model->danwei])->name."</dd>
                              <dd>采购方式：".Category::findOne(['id'=>$model->caigoufangshi])->name."</dd>
                              <dd>预算总额：".$model->zonge."万元</dd>
                              <dd>申请账号：".User::findOne($model->userid)->username."</dd>
                              <dd>申请事项：".StringHelper::truncate_utf8_string($model->shixiang,20)."</dd></dl>";
                    return $v;
                }
            ],
            [
                'attribute' => 'progress',
                'label' => '流程',
                'content' => function($model) {
                
                    $v = '<dl>';
                    $r = $model->getPrevResult();
                    if(!empty($r)){
                        $v .= "<dd>".$r['tip']."</dd>";
                    }
                    
                    $v .= "<dd>当前步奏：<span class='label label-primary'>".Procurement::$STATUS[$model->status]['name']."</span></dd>
                           <dd>下一步奏：<span class='label label-success'>".$model->getNext()['name']."</span></dd></dl>";
                    return $v;
                }
            ],
            
            [
                'attribute' => 'action',
                'label' => '操作',
                'content' => function($model){
                    return $model->getActionBtns();
                }
            ]


        ],
    ]); ?>
<p>
流程：
<?php 
$progress = Procurement::getProgress();
foreach($progress as $k=>$v){
    $s = $v['name'];
    if($k!='finished')
        $s .= " --> ";
    
    echo $s;
}
?>
</p>
</div>
