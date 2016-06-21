<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use funson86\setting\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Procurement */

$this->title = '修改用户：'.$model->name;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';

$roles = Yii::$app->authManager->getRoles();
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-update']); ?>
    
        <?php echo $form->field($model, 'roles')->widget(Select2::classname(), [
            'hideSearch'=>false,
            'data' => ArrayHelper::map($roles,'name','description'),
            'language' => 'zh-CN',
            'options' => ['placeholder' => '请选择...',multiple=>true],
            'pluginEvents' =>[
                "select2:closing" => "function() { $('#userupdate-roles').blur(); }",
            ]
        ])->label("分配用户角色（多选）");
        ?>
        
        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'name')->label('使用主体(账号的所属主体，可以是个人姓名、单位名称、机构名称、公司名称等等)') ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'telephone') ?>
        
        <input style="display:none">
        <?= $form->field($model, 'newpassword')->passwordInput(['autocomplete'=>'off','value'=>''])->label('密码(不修改密码请留空)'); ?>
        
        
        <div class="form-group">
            <?= Html::submitButton('确认修改', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        
    <?php ActiveForm::end(); ?>
    
    <?php 
    $this->registerJs('
        jQuery("#userupdate-role").on("change",function(){
            if(this.value=="预算单位"){
                jQuery(".collapse").hide();
                jQuery("#col-danwei").show();
            }else if(this.value == "财政局对口主管"){
                jQuery(".collapse").hide();
                jQuery("#col-ke").show();
            }else 
                jQuery(".collapse").hide();
        });
        
        jQuery("#userupdate-role").trigger("change");
    ');
    
    
    ?>

</div>