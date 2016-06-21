<?php

use funson86\setting\models\Category;
use mihaildev\elfinder\AFile;
use yii\web\View;
use yii\helpers\Url;
use backend\modules\procurement\models\FormSign;
use common\models\User;

use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\models\Procurement */

$this->title = '项目详情';
$this->params['breadcrumbs'][] = ['label' => '项目ID:'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '项目详情';

$money_uploads = explode("|",Yii::$app->setting->get('uploads'));
$this->registerJs("var settingMoney = ".(int)Yii::$app->setting->get('money')." ;
    var settingUploads = ". json_encode($money_uploads) .";
",View::POS_HEAD);

?>


<div class="procurement-view">
<style>
.view_border{border-left:1px solid #ccc;width:100%}
.view_td{border:1px solid #ccc; border-left:0; padding:0; width:50% ;  vertical-align:top}
.view_block{padding:10px; border-bottom:1px solid #ccc;}
.view_block dl{margin:0;}
.view_block_end{border-bottom:0;}
.view_tip{margin-right:40px;}
.view_tip_right{margin-right:0; float:right;}
@media print{ .phidden{display:none;}}
</style>



<h2 class='text-center'>娄底市(<ins><?php echo  Category::findOne(['id'=>$model->xsq])->name;?></ins>县市区)政府采购项目申请书</h2>
    
<p>
    <label class='view_tip_right'>第<?php echo  $model->uploadpath;?>号</label>
    <label class='view_tip'>申报单位：<ins><?php echo  Category::findOne(['id'=>$model->danwei])->name;?></ins> </label>
    <label class='view_tip'>日期：<ins><?php echo  date('Y年m月d日',$model->starttime);?></ins></label>
</p>
    
<table class='view_border'>
    <tr>
    <td class='view_td'>
        <div class='view_block'>           
        <label>申报事项：</label>
        <p><ins><?php echo nl2br($model->shixiang);?></ins></p>   
        </div>
        
        <div class='view_block'>           
        <label>项目要求：</label>
        <p><ins><?php echo nl2br($model->yaoqiu);?></ins></p>   
        </div>
        
        <?php if(!empty($model->liyou)){?>
        <div class='view_block'>           
        <label>申报理由：</label>
        <p><ins><?php echo nl2br($model->liyou);?></ins></p>   
        </div>
        <?php }?>  
        
        <div class='view_block'>   
        <label>项目预算总额：<ins><?php echo $model->zonge;?></ins> 万元</label>
        </div>
        
        <div class='view_block view_block_end'>   
        <label>资金来源：</label>
        <ol>
            <li>本级预算安排：<ins><?php echo $model->laiyuan_yusuan;?></ins> 万元</li>
            <li>非税收入财力安排：<ins><?php echo $model->laiyuan_feishui;?></ins> 万元</li>
            <li>上级补助：<ins><?php echo $model->laiyuan_shangji;?></ins> 万元</li>
            <li>其他方式筹措：<ins><?php echo $model->laiyuan_qita;?></ins> 万元</li>
        </ol>
        </div>
    </td>
    
    
    
    <td class='view_td'>
        <div class='view_block'>
        <dl>     
        <dd><label>项目分类：<ins><?php echo Category::findOne(['id'=>$model->xiangmulei])->name;?></ins></label></dd>
        <dd><label>采购方式：<ins><?php echo Category::findOne(['id'=>$model->caigoufangshi])->name;?></ins></label></dd>
        <dd><label>采购单位经办人：<ins><?php echo $model->jingbanren;?></ins></label></ddd>
        <dd><label>联系电话：<ins><?php echo $model->telephone;?></ins></label></dd>
        <dd><label>招标代理机构：<ins><?php echo User::findOne($model->daili_id)->name;?></ins></label></dd>
        </dl>  
        </div>
        
        <?php if(Yii::$app->setting->get('needZhuguan')=='1'){?>
        <div class='view_block'>           
        <label>财政局对口主管科(股)资金来源审核意见：</label>
        <?php 
        if(  $haveright && $model->status=='finance' && empty($model->sign_finance_is) ) {
            echo $this->render('_signform', [
                'model' => new FormSign(),
            ]);
        } else if(!empty($model->sign_finance_time)) { ?>
        <p><ins><?php echo ($model->sign_finance_is?'同意':'不同意').'<br>'.nl2br($model->sign_finance);?></ins></p> 
        <p class='text-right'><ins><?php echo date('Y年m月d日',$model->sign_finance_time)?></ins></p> 
        <?php }else{  ?>
        <p><span style='color:red'>[未签署]</span></p>    
        <?php }?>      
        </div>
        <?php }?>
        
        <div class='view_block text-center'>           
        <label>政府采购审批意见</label>
        </div>
        
        <div class='view_block'>    
        <label>经办人意见：</label>
        <?php 
        if( $haveright && $model->status=='oprator' && empty($model->sign_oprator_is)) {
            echo $this->render('_signform', [
                'model' => new FormSign(),
            ]);
        } else if(!empty($model->sign_oprator_time)) { ?>
        <p><ins><?php echo ($model->sign_oprator_is?'同意':'不同意').'<br>'.nl2br($model->sign_oprator);?></ins></p> 
        <p class='text-right'><ins><?php echo date('Y年m月d日',$model->sign_oprator_time)?></ins></p> 
        <?php }else{  ?>
        <p><span style='color:red'>[未签署]</span></p>    
        <?php }?>      
        </div>           
        
        
        <div class='view_block view_block_end'>
        <label>负责人意见：</label>
        <?php 
        if($haveright && $model->status=='head' && empty($model->sign_head_is)) {
            echo $this->render('_signform', [
                'model' => new FormSign(),
            ]);
        } else if(!empty($model->sign_head_time)) { ?>
        <p><ins><?php echo ($model->sign_head_is?'同意':'不同意').'<br>'.nl2br($model->sign_head);?></ins></p> 
        <p class='text-right'><ins><?php echo date('Y年m月d日',$model->sign_head_time)?></ins></p> 
        <?php }else{  ?>
        <p><span style='color:red'>[未签署]</span></p>    
        <?php }?>          
        </div>
    </td>
    </tr>
</table>
<p>&nbsp;</p>
<label>专家信息</label>
    <?= GridView::widget([
        'dataProvider' => $experts,
        'columns' => [
            [
    			'label' => '专家代码',
	    		'attribute' => 'code',
	    		'value' => 'code'
    		],
    		
            [
    			'label' => '专家姓名',
	    		'attribute' => 'name',
	    		'value' => 'name'
    		],
    		
            [
    			'label' => '专家电话',
	    		'attribute' => 'phone',
	    		'value' => 'phone'
    		],
    		
            [
    			'label' => '专家分类',
	    		'attribute' => 'type2',
	    		'value' => 'type2'
    		],
    		
		]
    ]) ?>
<div class='phidden'>
<p>
<label>附件列表：<?php echo AFile::widget([
                        'pid' => $model->id,
                        'zonge' => $model->zonge,
                        'navlist' => $model->getNavList(),
                        'name' => 'uploadpath',
                        'path' => 'procurement/'.$model->uploadpath,
                        'aOptions' => ['class' => 'aupload_btn'],
                        'aName' => '查看附件',
]);?></label>
</p>


<iframe name='print_doc' id='print_doc' style='display: none;'></iframe>
<script type="text/javascript">
function preview(oper) { 
    	var newWindow=window.open("<?php echo Url::toRoute(['default/print', 'id' => $model->id,]);?>","sprint_doc");
    	newWindow.print(); 
    	//newWindow.close();
}
</script>
<p><button class='btn btn-info' onclick='preview(1);'>打印项目表格</button></p>
</div>

</div>
