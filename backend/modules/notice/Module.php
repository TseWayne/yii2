<?php

namespace backend\modules\notice;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\notice\controllers';
    public $mainLayout = '@backend/views/layouts/main.php';
    
    private $_coreItems = [
    'default/index' => '招标公告列表',
    'default/create' => '创建招标公告',
    
    //'report/index' => '数据报表',
    ];
    
    
    /**
     * @var array
     * @see [[items]]
     */
    private $_normalizeMenus;

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
   
    
    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
    	if (parent::beforeAction($action)) {
    		/* @var $action \yii\base\Action */
    		$view = $action->controller->getView();
    
    		$view->params['breadcrumbs'][] = [
    		'label' => '招标公告',
    		'url' => ['/' . $this->uniqueId]
    		];
    		return true;
    	}
    	return false;
    }
}
