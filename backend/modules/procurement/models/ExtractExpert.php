<?php

namespace backend\modules\procurement\models;

use Yii;
use yii\data\ActiveDataProvider;
use backend\modules\procurement\models\Expert;

/**
 * This is the model class for table "extract_expert".
 *
 * @property string $id
 * @property integer $pid
 * @property integer $expertId
 */
class ExtractExpert extends \yii\db\ActiveRecord
{
	
	public $phone;
	public $code;
	public $name;
	public $type2;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'extract_expert';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'expertId'], 'required'],
            [['pid', 'expertId'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'expertId' => 'Expert ID',
        ];
    }
    
    
    /**
     *  与 expert ‘专家表’ 建立关联
     *  
     */
    public function getExpert(){
    	return $this->hasMany(Expert::className(), ['id' => 'expertId']);
    }
    
    /* 
     * 关联 expert 查询专家数据
     * 
     */
    public static function getExpertList($id){
    	
    	$query = self::find();
    	$query->joinWith(['expert']);
    	$query->select('expert.code,expert.name,expert.phone,expert.type2');
    	$dataProvider = new ActiveDataProvider([
    		'query' => $query,
    		'pagination' => ['pageSize' => 20,],
    	]);
    	
    	/* $dataProvider->setSort([
    		'attributes' => [
    			'code' => [
	    			'asc' => ['expert.code' => SORT_ASC],
	    			'desc' => ['expert.code'=>SORT_DESC]
    			],
    			'name' => [
	    			'asc' => ['expert.name' => SORT_ASC],
	    			'desc' => ['expert.name'=>SORT_DESC]
    			],
    			'phone' => [
	    			'asc' => ['expert.phone' => SORT_ASC],
	    			'desc' => ['expert.phone'=>SORT_DESC]
    			],
    	
    		]
    	]); */
    	
    	$query->andFilterWhere([
    			'extract_expert.pid' => $id
    	]);
    	 
    	//echo("<p style='margin-left:20%'>".$query->createCommand()->getRawSql()."</p>");
    	return $dataProvider;
    }
    
}
