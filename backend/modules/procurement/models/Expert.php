<?php

namespace backend\modules\procurement\models;

use Yii;

/**
 * This is the model class for table "zhuanjias".
 *
 * @property string $id
 * @property string $name
 * @property integer $sex
 * @property string $idcard
 * @property string $phone
 * @property string $unit
 * @property string $unitplace
 * @property string $email
 * @property string $trade
 * @property string $code
 * @property string $education
 * @property string $type
 * @property string $recommend
 * @property string $area
 * @property string $level
 * @property string $create_at
 * @property string $update_at
 */
class Expert extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'expert';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'phone', 'unit', 'trade', 'code'], 'required'],
            [['sex'], 'integer'],
            [['name', 'unit', 'unitplace', 'email', 'trade', 'code', 'education', 'type', 'recommend', 'area', 'level', 'create_at', 'update_at'], 'string', 'max' => 255],
            [['idcard', 'phone'], 'string', 'max' => 20],
            [['type1', 'type2','type3'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '姓名',
            'sex' => '性别',
            'idcard' => '身份证',
            'phone' => '电话',
            'unit' => '单位',
            'unitplace' => '单位地址',
            'email' => '邮箱',
            'trade' => '行业',
            'code' => '专家代码',
            'education' => '学历',
            'type' => '专家类型',
            'recommend' => '推荐方式',
            'area' => '地区',
            'level' => '职称',
            'create_at' => '创建时间',
            'update_at' => '更新时间',
            'type1' => "一级分类",
            'type2' => "二级分类",
            'type3' => "三级分类"
        ];
    }
    
    /**
     * 性别
     * @return array
     */
    public static function getSex()
    {
    	return [
	    	'1' => '男',
	    	'0' => '女',
    	];
    }
    
    /**
     * 性别区分 view
     * @return string
     */
    public static function getSexText($val)
    {
    	if($val == 1){
    		return "男";
    	}else{
    		return "女";
    	}
    }
    
    /**
     * 专家分类 select
     * @return string
     */
    public static function zjSelect()
    {
    	$typeArr = \Yii::$app->params['typeArr'];//获取数组
    	
		foreach ($typeArr as $value){
		    
		        foreach ($value['son'] as $val){
		        	
		        		foreach ($val['son'] as $va){
		        			if(count($va['son'])>0){
		        				foreach ($va['son'] as $v){
		        					$arr[3][$va['name']."-".$va['code']][$v['name']."-".$v['code']] = $v['name']."-".$v['code'];
		        				}
		        			}
		        			$arr[2][$val['name']."-".$val['code']][$va['name']."-".$va['code']] = $va['name']."-".$va['code'];
		        		}
		        		$arr[1][$val['name']."-".$val['code']] = $val['name']."-".$val['code'];
		        	
		        }
		    	$arr[0][] = $value['name'];
		    
        }
        return $arr;
    }
    
    
    
   
}
