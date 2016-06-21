<?php

namespace funson86\setting;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'funson86\setting\controllers\frontend';
    public $mainLayout = '@backend/views/layouts/main.php';

    protected $_isBackend;
    
    private $_coreItems = [
        'category/index' => '分类管理',
        'default/index' => '全局设置',
    ];
    /**
     * @var array
     * @see [[items]]
    */
    private $_normalizeMenus;

    public function init()
    {
        parent::init();

        //$this->setViewPath('@funson86/setting/views');
    }
    
    public function getMenus()
    {
        if ($this->_normalizeMenus === null) {
            $mid = '/' . $this->getUniqueId() . '/';
            // resolve core menus
            $this->_normalizeMenus = [];

            foreach ($this->_coreItems as $id => $lable) {
                $this->_normalizeMenus[$id] = ['label' => $lable, 'url' => [$mid . $id]];
            }

        }
        return $this->_normalizeMenus;
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
                'label' => '分类设置',
                'url' => ['/' . $this->uniqueId .'/category']
            ];
            return true;
        }
        return false;
    }

    /**
     * Translates a message to the specified language.
     *
     * This is a shortcut method of [[\yii\i18n\I18N::translate()]].
     *
     * The translation will be conducted according to the message category and the target language will be used.
     *
     * You can add parameters to a translation message that will be substituted with the corresponding value after
     * translation. The format for this is to use curly brackets around the parameter name as you can see in the following example:
     *
     * ```php
     * $username = 'Alexander';
     * echo \Yii::t('app', 'Hello, {username}!', ['username' => $username]);
     * ```
     *
     * Further formatting of message parameters is supported using the [PHP intl extensions](http://www.php.net/manual/en/intro.intl.php)
     * message formatter. See [[\yii\i18n\I18N::translate()]] for more details.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current
     * [[\yii\base\Application::language|application language]] will be used.
     *
     * @return string the translated message.
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('funson86/' . $category, $message, $params, $language);
    }

    /**
     * Check if module is used for backend application.
     *
     * @return boolean true if it's used for backend application
     */
    public function getIsBackend()
    {
        if ($this->_isBackend === null) {
            $this->_isBackend = strpos($this->controllerNamespace, 'backend') === false ? false : true;
        }

        return $this->_isBackend;
    }
}
