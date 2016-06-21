<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \mdm\admin\models\form\Signup */


$this->title = '添加用户';
$this->params['breadcrumbs'][] = $this->title;


$roles = Yii::$app->authManager->getRoles();
?>
<div class="user-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
    
        
        <?php echo $form->field($model, 'roles')->widget(Select2::classname(), [
            'hideSearch'=>false,
            'data' => ArrayHelper::map($roles,'name','description'),
            'language' => 'zh-CN',
            'options' => ['placeholder' => '请选择...',multiple=>true],
            'pluginEvents' =>[
                "select2:closing" => "function() { $('#signup-roles').blur(); }",
            ]
        ])->label("分配用户角色（多选）");
        ?>
            
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'name')->label('使用主体(账号的所属主体，可以是个人姓名、单位名称、机构名称、公司名称等等)') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'telephone') ?>
        
        <input style="display:none">
        <?= $form->field($model, 'password')->passwordInput(['autocomplete'=>'off']) ?>
        
        
        <div class="form-group">
            <?= Html::submitButton('确认添加', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        
    <?php ActiveForm::end(); ?>


</div>
