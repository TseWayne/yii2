<?php
/**
 * Date: 23.01.14
 * Time: 1:27
 */

namespace mihaildev\elfinder;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;



class AFile extends InputWidget{
	public $language;

	public $filter;

	public $aTag = 'a';
	public $aName = 'Browse';
	public $aOptions = [];

	protected $_managerOptions;

	public $width = 'auto';
	public $height = 'auto';

	public $template = '{a}';

	public $controller = 'elfinder';

	public $path; // work with PathController

	public $multiple;
	public $navlist;
	public $zonge;
	public $pid;

	public function init()
	{
		parent::init();

		if(empty($this->language))
			$this->language = ElFinder::getSupportedLanguage(Yii::$app->language);

		if(empty($this->aOptions['id']))
			$this->aOptions['id'] = $this->options['id'].'_upload';

		
		$managerOptions = [];
		if(!empty($this->filter))
			$managerOptions['filter'] = $this->filter;

		$managerOptions['callback'] = $this->options['id'];

		if(!empty($this->language))
			$managerOptions['lang'] = $this->language;

		if (!empty($this->multiple))
			$managerOptions['multiple'] = $this->multiple;

		if(!empty($this->path))
			$managerOptions['path'] = $this->path;
		
		if(!empty($this->navlist))
		    $managerOptions['navlist'] = $this->navlist;
		
		if(is_numeric($this->pid))
		    $managerOptions['pid'] = $this->pid;

		$this->_managerOptions['url'] = ElFinder::getManagerUrl($this->controller, $managerOptions);
		$this->_managerOptions['width'] = $this->width;
		$this->_managerOptions['height'] = $this->height;
		$this->_managerOptions['id'] = $this->options['id'];
		
		if(!empty($this->zonge))
		$this->_managerOptions['zonge'] = $this->zonge;
		
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{

	    $this->aOptions['href'] = "javascript:mihaildev.elFinder.openManager(" . Json::encode($this->_managerOptions) . ");";
		$replace['{a}'] = Html::tag($this->aTag,$this->aName, $this->aOptions);

		echo strtr($this->template, $replace);

		AssetsCallBack::register($this->getView());

	}
}
