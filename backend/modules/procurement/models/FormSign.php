<?php

namespace backend\modules\procurement\models;

use Yii;
use yii\base\Model;
use backend\modules\procurement\models\Procurement;

/**
 * ProcurementSearch represents the model behind the search form about `app\models\Procurement`.
 */
class FormSign extends Procurement
{
    public $sis;
    public $smemo;
    public $stime;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['smemo','sis'], 'required'],
            ['smemo', 'string'],
            ['stime', 'default' , 'value' => time()],
            [['sis', 'stime'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    public function attributeLabels()
    {
        return [
            'sis' => '审核结果',
            'smemo' => '意见内容',
            'stime' => '签署日期',
        ];
    }
    
    
    public function save($run=true,$attribute=null){
        
        if($this->validate()){
            if(!in_array($this->status,array('finance','oprator','head'))){
                $this->addError('status', 'wrong status:'.$this->status);
                return false;
            }
            $n = 'sign_'.$this->status;
            $nis = $n.'_is';
            $ntime = $n.'_time';
            
            $this->$n = $this->smemo;
            $this->$nis = $this->sis;
            $this->$ntime = time();
            $tmp = $this->status; 
            $this->status = $this->sis?$this->getNext()['key']:'govement';
            $this->oldstatus = $tmp;
            $this->edittime = time();
            
            if($this->status == 'finished')
                $this->endtime = time();
            
            $attribute = [
                $n ,
                $nis ,
                $ntime ,
                'oldstatus' ,
                'status',
                'edittime',
                'endtime'
            ];
            return parent::save($run,$attribute);
        }else return false;
        
        
    }
    
    public function inserLog($insert){
        $log = new ActionLog();
        $log->userid = Yii::$app->user->id;
        $log->pid = $this->id;
        $log->time = time();
        $log->ip = Yii::$app->request->userIP;
        $log->memo = '用户('.Yii::$app->user->identity->username.')签署'.self::$STATUS[$this->oldstatus]['name']."；\r\n结果：".($this->sis?'同意':'不同意')."；\r\n意见：".$this->smemo.";\r\n项目：".$this->shixiang."。";
        $log->pis = $this->sis;
        $log->pstatus = $this->oldstatus;
        
        $log->save();
    }
    


}
