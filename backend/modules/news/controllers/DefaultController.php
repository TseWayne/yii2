<?php

namespace backend\modules\news\controllers;

use Yii;
use backend\modules\news\models\News;
use backend\modules\news\models\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\ImageUpload;

/**
 * DefaultController implements the CRUD actions for News model.
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
    	"imagePathFormat" => "/upload/News/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
    	],
    	]
    	];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
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
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post())) {
        	
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
	        
        	if ($model->save()){
        		return $this->redirect(['view', 'id' => $model->id]);
        	}
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
        	
        	$userName = yii::$app->user->identity->username;
        	$model->author = $userName;
        	$model->update_at = time();
        	//截取content中的第一张图作为封面图片
        	preg_match_all("/src=\"\/?(.*?)\"/", $model->content, $match);
        	$img = $match[1][0];
        	
        	
        	if($img){
        		ImageUpload::imgZip($img,550,550,2);
        		$model->thumb = $img;
        	}else{
        		if($model->thumb){
	        		@unlink($_SERVER['DOCUMENT_ROOT'].$model->thumb);
	        		@unlink($_SERVER['DOCUMENT_ROOT'].$model->thumb.'_x.jpg');	
        		}
        		$model->thumb = '';
        	}
        	
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
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
    	$model = $this->findModel($id);
    	
        $bb = $model->delete();
        if($bb){
        	@unlink($_SERVER['DOCUMENT_ROOT'].$model->thumb);
	        @unlink($_SERVER['DOCUMENT_ROOT'].$model->thumb.'_x.jpg');	
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
        	/* if (!$model->thumb){
        		$model->thumb = "upload/image/nopic.jpg";
        	} */
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
