<?php

namespace backend\modules\notice\controllers;

use Yii;
use backend\modules\notice\models\Notice;
use backend\modules\notice\models\NoticeSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use backend\models\ImageUpload;


/**
 * 招标公告管理   
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            
        ];
    }
    
    /**
     * 富文本编辑器配置
     * @return mixed
     */
    public function actions()
    {
    	$rootPath = "";//图片路径前缀
    	return [
	    	'upload' => [
	    		'class' => 'kucha\ueditor\UEditorAction',
	    		'config' => [
		    		"imageUrlPrefix"  => $rootPath,//图片访问路径前缀
		    		"imagePathFormat" => "/upload/notice/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
	    		],
	    	]
    	];
    }

    /**
     * Lists all Notice models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NoticeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Notice model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
    	
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Notice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Notice();

        /* if(\Yii::$app->user->isGuest){
        	//登陆
        	return $this->redirect(array('/site/login'));
        	
        } */
	
        if ($model->load(Yii::$app->request->post())) {
        	
        	$cgId = $model->cgId;
        	$userName = yii::$app->user->identity->username;
        	$model->author = $userName;
        	$model->create_at = time();
        	$model->update_at = time();
        	
        	//截取content中的第一张图作为封面图片
        	preg_match_all("/src=\"\/?(.*?)\"/", $model->content, $match);
        	$img = $match[1][0];
        	if($img){
        		ImageUpload::imgZip($img,550,550,2);
        		$model->thumb = $img;
        	}
        	
        	if($model->save()){
        		return $this->redirect(['view', 'id' => $model->id]);
        	}
        } else {
        	
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Notice model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        
        if ($model->load(Yii::$app->request->post())) {
        	//上传@生成缩略图   file上传
        	//$img = Notice::upload($model,'upload/notice/thumb/','thumb',true);
        	
        	preg_match_all("/src=\"\/?(.*?)\"/", $model->content, $match);
        	$img = $match[1][0];
        	if($img){
        		ImageUpload::imgZip($img,550,550,2);
        		$model->thumb = $img;
        	}
        	
        	//$model->thumb = $img;
	        $model->update_at = time();
	        $model->author = yii::$app->user->identity->username;
	        
	          
        	if($model->save()){
        		
           		return $this->redirect(['view', 'id' => $model->id]);
        	}
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Notice model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
         
        $bb = $model->delete();
        if($bb && $model->thumb){
        	@unlink($_SERVER['DOCUMENT_ROOT'].$model->thumb);
        	@unlink($_SERVER['DOCUMENT_ROOT'].$model->thumb.'_x.jpg');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Notice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Notice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
