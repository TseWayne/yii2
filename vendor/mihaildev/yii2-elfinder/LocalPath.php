<?php
/**
 * Date: 23.01.14
 * Time: 22:47
 */

namespace mihaildev\elfinder;

use Yii;
use backend\modules\procurement\models\Procurement;

class LocalPath extends BasePath{
	public $path;
	public $pid;

	public $baseUrl = '@web';

	public $basePath = '@webroot';
	private $_defaults;

	public function getUrl(){
		return Yii::getAlias($this->baseUrl.'/'.trim($this->path,'/'));
	}

	public function getRealPath(){
		$path = Yii::getAlias($this->basePath.'/'.trim($this->path,'/'));

		if(!is_dir($path))
			mkdir($path, 0777, true);

		return $path;
	}
	
	public function getDefaults(){
	    
	    $this->_defaults = parent::getDefaults();
	    $upath = explode("/",trim($this->path,"/"));
	    $upath = end($upath);
	    if(is_numeric($upath)){
	        $model = Procurement::findOne(['uploadpath'=>$upath]);
	        if($model==null)
	            $this->_defaults['write'] = true;
	        else if( $model->status=='govement'  && ($model->userid==\Yii::$app->user->id || \Yii::$app->user->can('elfinder_admin')) )
	            $this->_defaults['write'] = true;
	        else 
	           $this->_defaults['write'] = false;
	    }
	    
	    $this->_defaults['locked'] = !$this->_defaults['write'];
	    return $this->_defaults;
	}

	public function getRoot(){

		$options = parent::getRoot();

		$options['path'] = $this->getRealPath();
		$options['URL'] = $this->getUrl();
		$options['defaults'] = $this->getDefaults();

		return $options;
	}
} 