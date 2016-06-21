<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model,$pmodel common\models\Category */
/* @var $searchModel common\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $parents common\models\Category->parents() */

$this->title = '分类管理';
$this->params['breadcrumbs'][] = ['label' => '顶级分类', 'url' => ['index']];

if(!empty($pmodel)){
    $this->title = $pmodel->name;
    $parents = $pmodel->parents()->all();
    foreach($parents as $k=>$v){
        $this->params['breadcrumbs'][] = [
            'label' => $v->name, 
            'url' => [
                'index',
                'pid'=>$v->id
            ]
        ];
    }
    $this->params['breadcrumbs'][] = [
        'label' => $pmodel->name, 
        'url' => [
            'index',
            'pid'=>$pmodel->id
        ]
    ];
}
?>
<div class="Category-index">

<h1><?= Html::encode($this->title) ?></h1>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'name',
            [
                'attribute' => 'children_num',
                'label' => '子节点',
                'value' => function($model) {
                    $children = $model->children()->all();
                    return count($children);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function($url,$model,$key){
                        $options = [
                            'title' => '查看',
                            'aria-label' => '查看',
                            'data-pjax' => '0',
                        ];
                        $nurl = ['index','pid'=>$model->id];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',$nurl, $options);
                    },
                    'update' => function($url,$model,$key){
                        $options = [
                            'title' => '修改',
                            'aria-label' => '修改',
                            'data-pjax' => '0',
                        ];
                        $pid = Yii::$app->request->queryParams[pid];
                        $nurl = ['index','id'=>$model->id,'pid'=>$pid];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>',$nurl, $options);
                    },
                    'delete' => function($url,$model,$key){
                        $options = [
                            'title' => '删除',
                            'aria-label' => '删除',
                            'data-confirm' => '您确定要删除此项吗？',
                            'data-method' => 'post',
                            'data-pjax' => '0',
                        ];
                        $pid = Yii::$app->request->queryParams[pid];
                        $nurl = ['delete','id'=>$model->id,'pid'=>$pid];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',$nurl, $options);
                    },
                ]
            ],
        ],
    ]); ?>



<?php  echo $this->render('_create', ['model' => $model,'pid'=>$pmodel->id]); ?>


</div>
