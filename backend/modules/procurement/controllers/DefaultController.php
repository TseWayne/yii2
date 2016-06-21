<?php

namespace backend\modules\procurement\controllers;

use Yii;
use backend\modules\procurement\models\Procurement;
use backend\modules\procurement\models\ProcurementSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\modules\procurement\models\ActionLogSearch;
use backend\modules\procurement\models\FormSign;
use yii\base\Exception;

use backend\modules\procurement\models\ExtractExpert;

/**
 * 采购项目公共控制器，项目相关角色都可以访问，包含了项目列表、详情、签署、操作记录 等操作。
 */
class DefaultController extends Controller
{
   
    /**
     * Lists all Procurement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProcurementSearch();
        if(\Yii::$app->user->can('procurement_crud') && !\Yii::$app->user->can('procurement_sign_jing')
            && !\Yii::$app->user->can('procurement_sign_fuze') ){
            $searchModel->loadUserid(\Yii::$app->user->id);
        }
        $searchModel->loadStatus('finished');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionTask()
    {
        $searchModel = new ProcurementSearch();
        
        $right = array();
        if(\Yii::$app->user->can('procurement_crud') && !\Yii::$app->user->can('procurement_sign_jing')
            && !\Yii::$app->user->can('procurement_sign_fuze') ){
            $searchModel->loadUserid(\Yii::$app->user->id);
        }
        $searchModel->loadStatus(['govement','oprator','head']);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('task', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all Procurement models action logs.
     * @return mixed
     */
    public function actionLogs()
    {
        $searchModel = new ActionLogSearch();
        $pid = Yii::$app->request->queryParams['pid'];
        
        $searchModel->loadUserid(\Yii::$app->user->id);
        if(is_numeric($pid) && $pid >0){
            //查看项目日志
            $searchModel->loadUserid(null);
            if( !\Yii::$app->user->can('actionlog_admin') ){ 
                //非日志管理员 检查项目id，不属于自己的项目，只列出自己相关日志
                $fid = Procurement::findOne($pid)->userid;
                if($fid!=\Yii::$app->user->id){
                    Yii::$app->getSession()->setFlash('info', "由于权限设置，只列出该项目与您相关的操作日志！");
                    $searchModel->loadUserid(\Yii::$app->user->id);
                }
            }
            $searchModel->loadPID($pid);
        }
        if(\Yii::$app->user->can('actionlog_admin')){
            $searchModel->loadUserid(null);
        }
        
        
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,true);
    
        return $this->render('logs', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Procurement model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = FormSign::findOne($id);
        if($model==null) {
            throw new Exception('id不存在');
            return;
        }
        
        $experts = ExtractExpert::getExpertList($id);
       
        
        $haveright = false;
        if( ($model->status == 'finance' && \Yii::$app->user->can('procurement_sign_zhuguan'))  ||
            ($model->status == 'oprator' && \Yii::$app->user->can('procurement_sign_jing'))  ||
            ($model->status == 'head' && \Yii::$app->user->can('procurement_sign_fuze'))  )
            $haveright = true;
        
        if( $haveright && $model->load(Yii::$app->request->post())) {
            if(!$model->save()){
                $errors = $model->firstErrors;
                throw new Exception(reset($errors));
            }
        }
        
        return $this->render('view', [
            'model' => $model,
            'haveright' => $haveright,
        	'experts' => $experts
        ]);
    }
    
    /**
     * Print a single Procurement model.
     * @param string $id
     * @return mixed
     */
    public function actionPrint($id)
    {
    	
    	$experts = ExtractExpert::getExpertList($id);
    	
        $this->layout = "@backend/views/layouts/print";
        return $this->render('view', [
            'model' => $this->findModel($id),
        	'experts' => $experts,
        ]);
    }

    
    /**
     * Finds the Procurement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Procurement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Procurement::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
