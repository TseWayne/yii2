<?php

namespace backend\modules\procurement\controllers;

use Yii;
use backend\modules\procurement\models\Procurement;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Exception;

/**
 * 采购项目预算单位专用控制器，包含预算单位对采购项目的 添加、修改、提交、删除 等操作
 */
class GovementController extends Controller
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    /**
     * Creates a new Procurement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Procurement();
        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post()['next']){
                $model->status = Procurement::$STATUS['govement']['next'];
                $model->oldstatus = 'govement';
            }else{
                $model->status = 'govement';
                $model->oldstatus = 'govement';
            }
            
            if($model->save())
                return $this->redirect(['default/view', 'id' => $model->id]);
            else{
                $errors = $model->firstErrors;
                throw new Exception(reset($errors));
            }
            
        } else {
            $model->starttime = date("Y-m-d");
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Procurement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if(Yii::$app->request->post()['next']){
                $model->status = $model->getNext()['key'];
                $model->oldstatus = 'govement';
            }
            
            if($model->save())
                return $this->redirect(['default/view', 'id' => $model->id]);
            else{
                $errors = $model->firstErrors;
                throw new Exception(reset($errors));
            }
        } 
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }   
    
    public function actionSubmit($id)
    {
        $model = $this->findModel($id);
        $model->status = $model->getNext()['key'];
        $model->oldstatus = 'govement';
        $model->edittime = time();
        if($model->save(true,['status','oldstatus','edittime']))
            return $this->redirect(['default/view', 'id' => $model->id]);
        else{
            $errors = $model->firstErrors;
            throw new Exception(reset($errors));
        }
    }

    /**
     * Deletes an existing Procurement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['default/task']);
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
