<?php

namespace backend\modules\procurement\models;

use Yii;
use funson86\setting\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * This is the model class for table "procurement".
 *
 * 
 * $xiangmu_name
 * $gongying
 * $price
 * $pingwei
 * $gongshi_time
 * $kaibiao_time
 * $kaibiao_address
 * $jiandu
 * $jiandu_tel
招标项目名称  
中标供应商名单、中标价格
评标委员会成员名单
招标采购单位的名称和电话及联系人
结果公示时间
开标时间，地点
监督部门及联系电话
 */
class Procurement extends \yii\db\ActiveRecord
{
    
    //流程配置文件
    public static $STATUS = [
        'govement' => [
            'key'=>'govement',
            'name'=>'项目修改',
            'save'=>'govement',
            'next'=>'finance',
            'style'=>'label label-primary',
            'tip'=>'<span class="text-info">等待项目提交，确认信息之后请尽快提交项目</span>'],
        'finance' => [
            'key'=>'finance',
            'name'=>'财政局对口主管科意见',
            'save'=>'govement',
            'next'=>'oprator',
            'style'=>'label label-primary',
            'tip'=>'<span class="text-info">等待财政局对口主管审核</span>'],
        'oprator' => [
            'key'=>'oprator',
            'name'=>'采购办经办人意见',
            'save'=>'govement',
            'next'=>'head',
            'style'=>'label label-primary',
            'tip'=>'<span class="text-info">等待采购办经办人意见审批</span>'],
        'head' => [
            'key'=>'head',
            'name'=>'采购办负责人意见',
            'save'=>'govement',
            'next'=>'finished',
            'style'=>'label label-primary',
            'tip'=>'<span class="text-info">等待采购办负责人意见审批</span>'],
        'finished' => ['key'=>'finished',
            'name'=>'项目完成',
            'save'=>'',
            'next'=>'',
            'style'=>'label label-success',
            'tip'=>'<span class="text-info">项目完成</span>'],
    ];
    
    //获取上一步结果
    public function getPrevResult(){
        
        if(empty($this->oldstatus))
            return null;
        
        //上一步不需要审核，直接返回上一步
        if(in_array($this->oldstatus,array('govement','finished'))){
            return ['is'=>1,'tip'=>self::$STATUS[$this->status]['tip'],'prev'=>self::$STATUS[$this->oldstatus]];
        }
        
        //上一步需要审核，返回上一步的同时，返回审核结果
        $n = 'sign_'.$this->oldstatus;
        $nis = $n.'_is';
        $ntime = $n.'_time';
        
        if($this->$nis){
            $s = self::$STATUS[$this->status]['tip'];
        }else   
            $s = '<span class="text-danger">'.self::$STATUS[$this->oldstatus]['name'].' 审核未通过：<br>'.nl2br($this->$n).'</span>';
        
        return ['memo' => $this->$n,
              'is' => $this->$nis,
              'time' => $this->$ntime,
              'tip' => $s,
              'prev' => self::$STATUS[$this->oldstatus],
        ];
    }
    
    public function getActionBtns(){
        $btns = '';
        switch ($this->status){
            case 'govement':
                if(($this->userid == \Yii::$app->user->id && \Yii::$app->user->can('procurement_crud')) || 
                \Yii::$app->user->can('procurement_admin'))
                {
                    $btns .= Html::a('修改',['govement/update','id'=>$this->id,'menu'=>'task']);
                    $btns .= Html::a('提交',['govement/submit','id'=>$this->id],[
                        'data-confirm' => '您确定已经修改完成，可以提交项目吗？',
                        'data-method'=>'post',
                    ]);
                    $btns .= Html::a('查看',['default/view','id'=>$this->id,'menu'=>'task']);
                    $btns .= Html::a('删除',['govement/delete','id'=>$this->id],[
                        'data-confirm' => '您确定要删除项目吗？',
                        'data-method'=>'post',
                    ]);
                }
                break;
            case 'oprator':
                if(Yii::$app->user->can('procurement_sign_jing'))
                $btns .= Html::a('签署意见',['default/view','id'=>$this->id]);
                break;
            case 'head':
                if(Yii::$app->user->can('procurement_sign_fuze'))
                $btns .= Html::a('签署意见',['default/view','id'=>$this->id]);
                break;
        }
        
        $btns .= Html::a('日志',['default/logs','pid'=>$this->id]);
        return $btns;
    }

    //获取下一步奏
    public function getNext(){
        //审核未通过，下一步指向跳转到审核不通过时保存的步奏
        $p = $this->getPrevResult();
        if(!empty($p) && $p['is']=='0')
            return $p['prev'];
        
        //不需要审核,或者审核通过时，直接读取配置文件中的下一步奏
        $s = empty($this->status)?'govement':$this->status;
        $n = self::$STATUS[$s]['next'];
       
        //检查设置是否跳过主管步奏
        $isz = Yii::$app->setting->get('needZhuguan');
        if($isz=='0' && $n == 'finance')
            $n = self::$STATUS['finance']['next'];

        return self::$STATUS[$n];
    }
    
    //获取流程路线
    public static function getProgress(){
        if(Yii::$app->setting->get('needZhuguan')=='0')
            unset(self::$STATUS['finance']);
        
        return self::$STATUS;
    }
    
       
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'procurement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['xsq','danwei','jingbanren','telephone','daili_id','starttime','xiangmulei','caigoufangshi','shixiang','yaoqiu','zonge','laiyuan_yusuan', 'laiyuan_feishui', 'laiyuan_shangji', 'laiyuan_qita'], 'required'],
            [['shixiang', 'yaoqiu', 'liyou', 'sign_finance', 'sign_oprator', 'sign_head'], 'string'],
            [['zonge', 'laiyuan_yusuan', 'laiyuan_feishui', 'laiyuan_shangji', 'laiyuan_qita'], 'number'],
            [['xsq','daili_id','danwei','ke','xiangmulei', 'caigoufangshi', 'edittime', 'userid', 'sign_finance_is', 'sign_finance_time', 'sign_oprator_is', 'sign_oprator_time', 'sign_head_is', 'sign_head_time', 'endtime','is_extract'], 'integer'],
            [['jingbanren', 'daili_name'], 'string', 'max' => 255],
            [['telephone','uploadpath','starttime'], 'string', 'max' => 20],
            ['starttime', 'string', 'max' => 10],
            ['status', 'in', 'range' => array_keys(self::$STATUS)],
            ['uploadpath','backend\modules\procurement\components\UploadPathCheckValidator'],
            ['liyou','backend\modules\procurement\components\MoneyCheckValidator'],
            ['zonge','backend\modules\procurement\components\CompareValidator'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'xsq' => '县市区',
            'danwei' => '申报单位',
            'shixiang' => '申报事项',
            'yaoqiu' => '项目要求',
            'liyou' => '申报理由',
            'zonge' => '总额(万)',
            'laiyuan_yusuan' => '本级预算安排(万元)',
            'laiyuan_feishui' => '非税收入财力安排(万元)',
            'laiyuan_shangji' => '上级补助(万元)',
            'laiyuan_qita' => '其他方式筹措(万元)',
            'telephone' => '联系电话',
            'starttime' => '申请时间',
            'edittime' => '申请人修改时间',
            'userid' => '申请账号id',
            'jingbanren' => '经办人',
            'xiangmulei' => '项目分类',
            'caigoufangshi' => '采购方式',
            'sign_finance' => '对口主管意见',
            'sign_finance_is' => '同意或者驳回',
            'sign_finance_time' => '签字时间',
            'sign_oprator' => '经办人意见',
            'sign_oprator_is' => '同意或者驳回',
            'sign_oprator_time' => '签字时间',
            'sign_head' => '负责人意见',
            'sign_head_is' => '同意或者驳回',
            'sign_head_time' => '签字时间',
            'endtime' => '完成日期',
            'daili_id' => '招标代理机构',
            'daili_name' => '代理公司名称',
            'status' => '状态',
        ];
    }
    
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            $this->starttime = strtotime($this->starttime);
            if( $this->starttime === false){
                $this->starttime = time();
            }
            $this->edittime = time();
            $this->userid = Yii::$app->user->id;
            return true;
        }else{
            return false;
        }
    }
    
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        $str = $insert?'添加':'修改';
        if($changedAttributes['status'])
            $str = '提交';
        $this->inserLog($str);
    }
    
    public function afterDelete(){
        parent::afterDelete();
        $this->inserLog('删除');
    }
    
    
    public function inserLog($type){
        $log = new ActionLog();
        $log->userid = Yii::$app->user->id;
        $log->pid = $this->id;
        $log->time = time();
        $log->ip = Yii::$app->request->userIP;
        $log->memo = '用户('.Yii::$app->user->identity->username.')'.$type."了采购项目：".$this->shixiang."。";
        $log->pis = 1;
        $log->pstatus = 'govement';
        $log->save();
    }
    
    
    public function getNavList(){
        if($this->caigoufangshi>0){
            return ArrayHelper::getColumn(Category::findOne(['id'=>$this->caigoufangshi])->children(1)->all(),'name');
        }else{
            $caigoufangshi = Category::findOne(['id'=>39])->children(1)->all();
            foreach(ArrayHelper::map($caigoufangshi,'id','name') as $k=>$v){
                $navlist[$k] = ArrayHelper::getColumn(Category::findOne(['id'=>$k])->children(1)->all(),'name');
            }
        }
        return $navlist;
    }   
        
}
