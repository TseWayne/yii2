<?php

namespace backend\modules\procurement;

use Yii;
use backend\modules\procurement\models\Procurement;
/**
 * procurement module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\procurement\controllers';
    public $mainLayout = '@backend/views/layouts/main.php';

    private $_coreItems = [
        'default/task' => '进行中的项目',
        'default/index' => '已完成的项目',
        'govement/create' => '申请采购项目',
        'default/logs' => '操作记录',
        //'report/index' => '数据报表',
    ];
    
    /**
     * @var array
     * @see [[items]]
    */
    private $_normalizeMenus;
    
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        // custom initialization code goes here
    }
    
    /**
     * Get avalible menu.
     * @return array
     */
    public function getMenus()
    {
        if ($this->_normalizeMenus === null) {
            $mid = '/' . $this->getUniqueId() . '/';
            // resolve core menus
            $this->_normalizeMenus = [];

            foreach ($this->_coreItems as $id => $lable) {
                if(!\Yii::$app->user->can('procurement_crud') &&  strstr($id,'govement'))
                    continue;
                $this->_normalizeMenus[$id] = ['label' => $lable, 'url' => [$mid . $id]];
            }
        }
        return $this->_normalizeMenus;
    }
    
    public function getTaskNum(){
        $right = array();
        if(\Yii::$app->user->can('procurement_crud'))
            $right[] = 'govement';
        if(\Yii::$app->user->can('procurement_sign_jing'))
            $right[] = 'oprator';
        if(\Yii::$app->user->can('procurement_sign_fuze'))
            $right[] = 'head';
        
        $query = Procurement::find();
        $k = array_search('govement', $right);
        if($k!==false){
            unset($right[$k]);
            $s = ['status'=>'govement'];
            if(!\Yii::$app->user->can('procurement_admin'))
                $s['userid'] = \Yii::$app->user->id;
            $query->andFilterWhere($s);
        }    
        $query->orFilterWhere(['status'=>$right]);  
        //echo("<p style='margin-left:20%'>".$query->createCommand()->getRawSql()."</p>");
        return $query->count();
    }
    
    public function setMenus($menus)
    {
        $this->_menus = array_merge($this->_menus, $menus);
        $this->_normalizeMenus = null;
    }
    
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            /* @var $action \yii\base\Action */
            $view = $action->controller->getView();
    
            $view->params['breadcrumbs'][] = [
                'label' => '政府采购',
                'url' => ['/' . $this->uniqueId]
            ];
            return true;
        }
        return false;
    }
    
  
}
