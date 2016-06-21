<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use funson86\setting\models\Category;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use mihaildev\elfinder\InputFile;
use yii\web\View;
use kartik\date\DatePicker;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Procurement */
/* @var $form yii\widgets\ActiveForm */

$leaves = Category::findOne(['id'=>29])->leaves()->all(); //申报单位
$xsq = Category::findOne(['id'=>49])->leaves()->all(); //县市区
$xiangmulei = Category::findOne(['id'=>25])->leaves()->all(); //项目分类
$caigoufangshi = Category::findOne(['id'=>39])->children(1)->all(); //采购方式


if($model->isNewRecord){
    list($usec, $sec) = explode(" ", microtime());
    $noname = date("ymdHis").(int)($usec*1000);
}else{
    $noname = $model->uploadpath;
}

$money_uploads = explode("|",Yii::$app->setting->get('uploads'));
$this->registerJs("\nvar settingMoney = ".(int)Yii::$app->setting->get('money')." ;
var settingUploads = ". json_encode($money_uploads) .";
",View::POS_HEAD);
?>

<div class="procurement-form">
    

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data','id'=>'form-procurement']]); ?>

    
    <h1>娄底市政府采购项目申请书</h1>
    <p>第<?= $noname ?>号</p><br/>
    
    <?php if(!$model->isNewRecord) {?>
    <p>审核意见：</p>
    <div class="form-group" style='color:red'>
    <?php if($model->sign_finance_is=='0' && $model->sign_finance_time){ echo nl2br($model->sign_finance); }?>
    <?php if($model->sign_oprator_is=='0' && $model->sign_oprator_time){ echo nl2br($model->sign_oprator); }?>
    <?php if($model->sign_head_is=='0' && $model->sign_head_time){ echo nl2br($model->sign_head); }?>
    </div>
    <br>
    <?php }?>
    
    <table width='100%'>
        <tr>
            <td width='24%' valign='top'>
                <?= $form->field($model, 'xsq')->dropDownList(ArrayHelper::map($xsq,'id','name'), [
                    'prompt' => '请选择...',
                ]); 
                ?>
            </td>
            <td width='10'></td>
            <td width='24%' valign='top'>
                <?= $form->field($model, 'xiangmulei')->dropDownList(ArrayHelper::map($xiangmulei,'id','name'), [
                    'prompt' => '请选择...',
                ]); 
                ?>
            </td>
            <td width='10'></td>
            <td width='24%' valign='top'>
                <?= $form->field($model, 'caigoufangshi')->dropDownList(ArrayHelper::map($caigoufangshi,'id','name'), [
                    'prompt' => '请选择...',
                ]); 
                ?>
            </td>
            <td width='10'></td>
            <td width='24%' valign='top'>
                <?php echo $form->field($model, 'danwei')->widget(Select2::classname(), [
                    'hideSearch'=>false,
                    'data' => ArrayHelper::map($leaves,'id','name'),
                    'language' => 'zh-CN',
                    'options' => ['placeholder' => '请选择...'],
                    'pluginEvents' =>[
                        "select2:closing" => "function() { $('#procurement-danwei').blur(); }",
                    ]
                ]);
                ?>
            </td>
        </tr>
        <tr>
            <td valign='top'>
                <?php echo $form->field($model, 'daili_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(User::findUsersByRole('agent'),'id','name'),
                    'language' => 'zh-CN',
                    'options' => ['placeholder' => '请选择...'],
                    'pluginEvents' =>[
                        "select2:closing" => "function() { $('#procurement-daili_id').blur(); }",
                    ]
                ]);
                ?>
            </td>
            <td></td>
            <td valign='top'>
                <?= $form->field($model, 'jingbanren') ?>
            </td>
            <td></td>
            <td valign='top'>
                <?= $form->field($model, 'telephone') ?>
            </td>
            <td></td>
            <td valign='top'>
                <?= $form->field($model, 'starttime')->widget(DatePicker::classname(), [
                    'type' => DatePicker::TYPE_INPUT,
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd'
                    ]
                ])?>
            </td>
        </tr>
    </table>
    
    <?= $form->field($model, 'shixiang')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'yaoqiu')->textarea(['rows' => 6])->label("项目要求(填写质量、售后服务、供货时间及其他要求)") ?>
    <?= $form->field($model, 'liyou')->textarea(['rows' => 6])->label("申请理由(预算总额大于".yii::$app->setting->get('money')."万需要填写，其他情况不需填写)") ?>

    <table width='100%'>
        <tr>
            <td colspan=7><label class="control-label">资金来源:(提示：“资金来源”合计数要与“项目预算总额”一致)</label><td>
            <td><label class="control-label">项目预算总额：</label></td>
        </tr>
        <tr>
            <td width='19%' valign='top'><?= $form->field($model, 'laiyuan_yusuan')->textInput(['maxlength' => true]) ?></td>
            <td align='center' width='10'>+</td>
            <td width='19%' valign='top'><?= $form->field($model, 'laiyuan_feishui')->textInput(['maxlength' => true]) ?></td>
            <td align='center' width='10'>+</td>
            <td width='19%' valign='top'><?= $form->field($model, 'laiyuan_shangji')->textInput(['maxlength' => true]) ?></td>
            <td align='center' width='10'>+</td>
            <td width='19%' valign='top'><?= $form->field($model, 'laiyuan_qita')->textInput(['maxlength' => true]) ?></td>
            <td align='center' width='10'>=</td>
            <td width='19%' valign='top'><?= $form->field($model, 'zonge')->textInput(['maxlength' => true ])->label('单位(万元)')?></td>
        </tr>
    </table>
    
    
    
    <?php
        $navlist = $model->getNavList();
        $this->registerJs("
            mihaildev.elFinder.openCheck = function(){
                if(!$('#procurement-caigoufangshi').val()){
                    $('#procurement-uploadpath').blur();
                    return false;
                }
                this.navlist = ".json_encode($navlist).";
                this.zonge = parseInt($('#procurement-zonge').val());
                this.navid = $('#procurement-caigoufangshi').val();
                $('#procurement-uploadpath').blur();
                return true;
            };
        ");
        echo $form->field($model, 'uploadpath')->widget(InputFile::className(),[
            'path' => 'procurement/'.$noname,
            'template' => '<input type="hidden" id="procurement-uploadpath" value="'.$noname.'" name="Procurement[uploadpath]"><span class="input-group-btn">{button}</span>',
            'buttonOptions' => ['class' => 'btn btn-default'],
            'buttonName' => '点击打开项目附件管理器',
        ])->label("项目附件(打开项目附件管理器，您需要按照左边的附件清单上传对应的附件)");
    ?>


    <div class="form-group">
        <?= Html::submitButton('保存项目', ['class' => 'btn btn-primary']) ?>
        <?= Html::hiddenInput('next','',['id'=>'next'])?>
        <?= Html::button('保存并提交项目', ['class' => 'btn btn-success', 'onclick'=>'dopost();']) ?>
        <p>提示：‘保存项目’按钮保存项目内容，但是不提交申请，您可以在保存项目内容之后在手动提交项目申请；‘保存并提交项目’按钮保存项目内容的同时，提交项目申请到相关部门，项目一经提交将不能再修改项目，直到项目在下一步的审核中不通过才能再次修改项目。</p>
    </div>


    <?php ActiveForm::end(); ?>
    
    <script type="text/javascript">
    function dopost(){
        jQuery("#next").val("1");
        jQuery("#form-procurement").get(0).submit();
    }
    </script>

</div>
