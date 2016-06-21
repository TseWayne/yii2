<?php

use yii\helpers\Html;
use yii\grid\GridView;
use funson86\setting\models\Category;
use mihaildev\elfinder\AFile;
use yii\web\View;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\procurement\models\ProcurementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '采购项目列表';
$this->params['breadcrumbs'][] = $this->title;

$money_uploads = explode("|",Yii::$app->setting->get('uploads'));
$this->registerJs("var settingMoney = ".(int)Yii::$app->setting->get('money')." ;
    var settingUploads = ". json_encode($money_uploads) .";
",View::POS_HEAD);


?>

<div class="procurement-index">

    <h1><?= Html::encode($this->title) ?></h1>

    
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            
            [
            'attribute' => 'caigoufangshi',
                'value' => function($model) {
                    return Category::findOne(['id'=>$model->caigoufangshi])->name;
            }
            ],
            [
                'attribute' => 'shixiang',
                'value' => function($model) {
                    return StringHelper::truncate_utf8_string($model->shixiang,20);
                }
            ],
            'zonge',/*
            [
                'attribute' => 'status',
                'content' => function($model) {
                    return Html::tag('span',Procurement::$STATUS[$model->status]['name'],['class'=>Procurement::$STATUS[$model->status]['style']]);
                },
                'filter' => ArrayHelper::map(Procurement::getProgress(),'key','name')
            ],*/
            [
                'attribute' => 'danwei',
                'value' => function($model) {
                    return Category::findOne(['id'=>$model->danwei])->name;
                }
            ],
            'starttime:date',
            'endtime:date',
            
            [
	            'format'=>'raw',
	            'value' => function($data){
		            $url = "index.php?r=procurement/expert/chouqu&id=".$data->id;
		            if($data->is_extract == 0){
		            	return Html::a('抽取专家', $url, ['title' => '抽取专家']);
		            }else{
		            	return "<span class='label label-success'>专家已抽取</span>";
		            }
	            }
            ],
            [
                'attribute' => 'uploadpath',
                'label' => '',
                'content' => function($model){
                    $btn = AFile::widget([
                        'pid' => $model->id,
                        'zonge' => $model->zonge,
                        'navlist' => $model->getNavList(),
                        'name' => 'uploadpath',
                        'path' => 'procurement/'.$model->uploadpath,
                        'aOptions' => ['class' => 'aupload_btn'],
                        'aName' => '附件',
                    ]);
                    
                    $btn .= " ".Html::a("查看",['view','id'=>$model->id,'menu'=>'index']);
                    $btn .= " ".Html::a('日志',['default/logs','pid'=>$model->id]);
                    return $btn;
                }
            ]


        ],
    ]); ?>
</div>
