<?php

namespace backend\modules\procurement\controllers;

use Yii;
use backend\modules\procurement\models\Expert;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\base\Exception;

use yii\helpers\Json;
use backend\modules\procurement\models\Procurement;
use backend\modules\procurement\models\ExtractExpert;
use yii\helpers\Url;

/**
 * ExpertController 专家处理.
 * 抽取；更换；下拉框；抽取后提交表带
 */
class ExpertController extends Controller
{

    /**
     * 专家分类获取 -- 联动下拉选择.（selectzj）
     * @return mixed
     */
    public function actionSelectzj()
    {
    
    	if(Yii::$app->request->isAjax){
    		$ajax = Yii::$app->request->post();
    		$msg = null;
    		
    		$keyAjax = $ajax['key'];
    		$arrKey = $ajax['arrKey'];
        	$arr = Expert::zjSelect();//所有专家分类
        	
    		foreach ($arr[$arrKey] as $key => $val){
    			if($key == $keyAjax){
    				if(is_array($val)){
    					foreach ($val as $k => $v){
    						$msg .= "<option value='".$k."'>".$v."</option>";
    					}
    				}
    				break;
    			}
    		}
    	}else{
    		$msg = "访问错误！";
    	}
    
    	return Json::encode($msg);
    
    }
    
    /**
     * 抽取页面.
     * If creation is successful, the browser will be redirected to the 'chouqu' page.
     * @return mixed
     */
    public function actionChouqu()
    {
    	 
    	if (Yii::$app->request->get()) {
    
    		$id = Yii::$app->request->get('id');
    		
    		$model = Procurement::findOne($id);
    		if($model==null) {
    			throw new Exception('id不存在');
    			return;
    		}
    		if($model->status != 'finished'){
    			Yii::$app->getSession()->setFlash('error', '该项目还在审批阶段！');
    			return $this->redirect(array('/procurement'));
    		}
    		if($model->is_extract){
    			Yii::$app->getSession()->setFlash('error', '该项目已抽取专家！');
    			return $this->redirect(array('/procurement'));
    		}
    
    		
    		//类别信息
    		$arr = Expert::zjSelect();
    		/*
    		 $lists = Zhuanjia::findBySql('SELECT * FROM `zhuanjia` WHERE typeId='.$typeId.' and id in ('.$ids.')')->asArray()->all();
    		var_dump($lists); 
    		*/
    
    		return $this->render('chouqu', [
	            	'arr' => $arr,
    				'model' => $model,
    				'id' => $id,
    				]);
    	} else {
    		throw new NotFoundHttpException('页面不存在！');
    	}
    }
    
    /**
     * 抽取处理.（ajax）
     * @return mixed
     */
    public function actionDo()
    {
    	 
    	if(Yii::$app->request->isAjax){
    
    		$ajax = Yii::$app->request->post();
    		$msg = array();
    		//专家
    		$type2 = $ajax['type2'];
    		
    		$list = Expert::find()->where(array('type2' => $type2))->asArray()->all();
    		
    		if(empty($list)){
    			$msg['status'] = "-1";
    			$msg['content'] = "暂无该类型的专家信息！";
    			return Json::encode($msg);
    		}
    		$count = count($list);
    		
    		$msg['type'] = $type2;
    		$msg['status'] = "1";
    		//随机数设定
    		$rand = array();
    		$num = $ajax['num']; //抽取人数
    		if($count >= $num){
    			while(count($rand)<$num){
    				$rand[] = rand(0,$count-1);
    				$rand = array_unique($rand);
    			}
    			//专家随机
    			foreach ($rand as $key){
    				$msg["content"][] = $list[$key];
    			}
    		}else{
    			$msg["content"] = $list;
    		} 
    	}else{
    		$msg['status'] = "-1";
    		$msg['content'] = "访问错误！";
    	}
    
    	return Json::encode($msg);
    
    }
    
    /**
     * 改变专家处理.（ajax）
     * @return mixed
     */
    public function actionChange()
    {
    
    	if(Yii::$app->request->isAjax){
    		$ajax = Yii::$app->request->post();
    		$msg = array();
    		//专家
    		$type2 = $ajax['type'];
    		$zjId = $ajax['zjId'];
    		$ids = explode(',', $zjId);
    
    		$list = Expert::find()->where(array('type2' => $type2))
    		->andWhere(array('not in', 'id', $ids))
    		->asArray()->all();
    		
    		if(empty($list)){
    			$msg['status'] = "-1";
    			$msg['content'] = "暂缺该类型的专家信息！";
    			return Json::encode($msg);
    		}
    		$count = count($list);
    		
    		$msg['status'] = "1";
    		//随机数设定
    		$rand = array();
    		$num = 1; //抽取人数
    		$rand = rand(0,$count-1);
    		$msg["content"] = $list[$rand];
    	}else{
    		$msg['status'] = "-1";
    		$msg['content'] = "访问错误！";
    	}
    
    	return Json::encode($msg);
    
    }
    
    /**
     * 提交专家抽取后的表单.
     * @return mixed
     */
    public function actionZjsubmit(){
    	$post = Yii::$app->request->post();
    	
    	if($post){
    		
    		$expertId = $post["zjId"];
    		$pid = $post['cgId'];
    		$expertNum = count($expertId);
    		$connection = Yii::$app->db;
    		$transaction = $connection->beginTransaction(); //开启事物处理
    	
    		try{
    			foreach ($expertId as $val){
    				$connection->createCommand()->insert(ExtractExpert::tableName(), [
    					'pid' => $pid,
    					'expertId' => $val,
    				])->execute();
    			}
    			$lastId = $connection->getLastInsertId();
    			
    			if($lastId){
    				$b = $connection->createCommand()->update(Procurement::tableName(), ['is_extract' => $expertNum], 'id = :id',array(':id'=>$pid))->execute();
    				
    			}
    			
    			$transaction->commit();
    		}catch(Exception $e){ 	//抛出异常
    			$transaction->rollBack();
    		}
    		
    		
    		if($b){
    			 
    			Yii::$app->getSession()->setFlash('success', '保存成功');
    			$url = Url::toRoute(['/procurement']);
    			return $this->redirect($url);
    		}else{
    			Yii::$app->getSession()->setFlash('error', '保存失败');
    			return $this->redirect(array('chouqu','id'=>$pid));
    		}
    
    
    	} else {
    		throw new NotFoundHttpException('页面不存在！');
    	}
    	 
    }
    

    /**
     * Finds the Expert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expert::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
