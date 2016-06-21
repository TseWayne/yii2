<?php

namespace backend\modules\notice\models;

use Yii;

use backend\modules\procurement\models\Procurement;

use yii\web\UploadedFile;

/**
 * This is the model class for table "notice".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property integer $cgId
 * @property string $cgName
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $clicks
 */
class Notice extends \yii\db\ActiveRecord
{
	
	
	const STATUS_ACTIVE = "1"; //已审核
	const STATUS_DISABLE = "0"; //未审核
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'notice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'cgId', 'create_at'], 'required'],
            [['content','author'], 'string'],
            [['cgId', 'create_at', 'update_at', 'clicks','status'], 'integer'],
            [['title','thumb'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '公告标题',
            'content' => '公告内容',
            'cgId' => '采购项目',
            'status' => '状态',
            'author' => '发布人',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
            'clicks' => '阅读量',
            'thumb' => '展示图',
        ];
    }
    
    /**
     * @inheritdoc  联接查询(采购项目名称)
     */
    public function getprocurement()
    {
    	return $this->hasOne(Procurement::className(),['id'=>'cgId']);
    }
    
    
    /**
     * @inheritdoc 获取所有采购项目id
     */
    public static function get_cg_id(){
    	$data = Procurement::find()->all();
    	$data = yii\helpers\ArrayHelper::map($data, 'id', 'shixiang');
    	return $data;
    }
    
    /**
     * @inheritdoc 获取对应采购项目id的名称
     */
    public static function get_cg_text($id){
    	$datas = self::get_cg_id();
    	return  $datas[$id];
    }
    
    /**
     * @inheritdoc 获取所有未发布公告的采购项目id
     */
    public static function get_cg_id_no_notice(){
    	$data = Procurement::find()->all();
    	$data = yii\helpers\ArrayHelper::map($data, 'id', 'shixiang');
    	
    	return $data;
    }
    
    
    /**
     * @inheritdoc
     */
    public static function getStatus()
    {
    	return [
    	'' => '全部',
    	self::STATUS_ACTIVE => '已审核',
    	self::STATUS_DISABLE => '未审核',
    	];
    }
    
}
