<?php
namespace funson86\setting\controllers;

use Yii;
use yii\web\Controller;
use funson86\setting\models\Category;
use yii\filters\VerbFilter;
use funson86\setting\models\CategorySearch;
use yii\db\Exception;
use yii\base\NotSupportedException;

/**
 * Site controller
 */
class CategoryController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get','post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        $id = Yii::$app->request->queryParams['id']; //修改的时候传递的model id
        $pid = Yii::$app->request->queryParams['pid']; //查看的时候传递的父节点 id
        $model = $this->findModel($id); //实例模型，id存在从数据库中查询并且绑定模型，否则新建模型
        if(is_numeric($pid)){ //实例父节点模型
            $pmodel = $this->findModel($pid);
        }
        
        if ($model->load(Yii::$app->request->post())) { //提交post数据，创建或者修改
            
            if($model->isNewRecord){
                if(empty($pmodel)){
                    $model->makeRoot();
                }else{
                    $model->prependTo($pmodel);
                }
                Yii::$app->getSession()->setFlash('success', "成功添加了一个节点！");
            }else{
                $model->save();
                Yii::$app->getSession()->setFlash('success', "成功修改了一个节点！");
            }
            
            return $this->refresh();
        } else { //显示列表
            
            $searchModel = new CategorySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
            return $this->render('index', [
                'pmodel' => $pmodel,
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } 
    }
    
    public function actionDelete($id,$pid=null)
    {
        
        try{
            $this->findModel($id)->delete();
            Yii::$app->getSession()->setFlash('success', "成功删除了节点！");
        }catch(Exception $e){
            Yii::$app->getSession()->setFlash('error', "不允许删除新的节点！");
        }catch(NotSupportedException $e){
            Yii::$app->getSession()->setFlash('error', "不支持删除含有子节点的顶级节点！");
        }
        
        return $this->redirect(['index','pid'=>$pid]);
    }

    
 

    protected function findModel($id)
    {
        if (is_numeric($id) && ($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            return new Category();
        }
    }
}
