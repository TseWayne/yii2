<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;
use mdm\admin\components\MenuHelper;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <!-- 不在支持老旧的IE5/6/7，最低支持到IE8，老旧IE跳转到升级页面。 -->
    <!--[if lte IE 7]>
    <script> window.location.href="ietips.html";</script>
    <![endif]-->
    <?php $this->head() ?>
    
    <!-- 兼容IE8 让IE8执行HTML5以及媒体查询 -->
    <!--[if IE 8]>
    <script src="./js/html5shiv.min.js"></script>
    <script src="./js/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<?php $this->beginBody() ?>


<?php
NavBar::begin([
    'brandLabel' => Yii::$app->setting->get('siteName'),
    'brandUrl' => Yii::$app->homeUrl,
    'innerContainerOptions' => ['class' => 'container-fluid'],
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);

$usermenu = MenuHelper::getAssignedMenu(Yii::$app->user->id);
foreach($usermenu as $i => $menu){
    $usermenu[$i]['active'] = strpos(trim($menu['url'][0], '/'),$this->context->module->id) === 0;
}
echo Nav::widget([
    'options' => ['class' => 'nav navbar-nav'],
    'items' => $usermenu
]);


$menuItems = [];
if (Yii::$app->user->isGuest) {
    $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
} else {
    $menuItems[] = '<li>'
        . Html::beginForm(['/site/logout'], 'post',['class'=>'navbar-form'])
        . Html::submitButton(
            'Logout (' . Yii::$app->user->identity->username . ')',
            ['class' => 'btn btn-link']
        )
        . Html::endForm()
        . '</li>';
}
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => $menuItems,
]);
NavBar::end();

?>



<div class="container-fluid">
    <div class="row">
        <div class="col-xs-2 sidebar">
            <div class="list-group">
                <?php
                $controller = $this->context;
                if($controller->module->hasProperty('menus')){
                    $menus = $controller->module->menus;
                    $route = $controller->route;
                    
                    if($controller->module->hasMethod('getTaskNum'))
                    $taskNum = $controller->module->getTaskNum();
                    foreach ($menus as $i => $menu) {
                        $label = Html::tag('i', '', ['class' => 'glyphicon glyphicon-chevron-right pull-right']) .
                            Html::tag('span', Html::encode($menu['label']), []);
                        if(strpos($i,'task') > 0 && $taskNum>0) $label .= Html::tag('span', $taskNum, ['class'=>'label label-danger pull-right']);
                        
                        
                        if(Yii::$app->request->get()['menu'] && strpos($i,Yii::$app->request->get()['menu']) > 0)
                            $active = ' active';
                        else 
                        $active = strpos($route, trim($menu['url'][0], '/')) === 0 ? ' active' : '';
                        
                     echo Html::a($label, $menu['url'], [
                            'class' => 'list-group-item' . $active,
                        ]);
                    }
                }
                ?>
            </div>
        </div><!-- end col2 sidebar-->
        <div class="col-xs-10 col-xs-offset-2">
            <div class="content-body">
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <?= Alert::widget() ?>
                <?= $content ?>
                
                
                <footer class="footer">
                    <div class="row">
                        <p class="col-xs-6">&copy; 湖南省娄底市政府采购办 <?= date('Y') ?></p>
                        <p class="col-xs-6 text-right">技术支持：湖南慧迅信息科技有限公司</p>
                    </div>
                </footer>
            </div><!-- end contentbody -->
        </div><!-- end col10 -->
    </div><!-- end row -->
</div><!-- end container -->





<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
