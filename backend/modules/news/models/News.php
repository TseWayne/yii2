<?php

namespace backend\modules\news\models;

use Yii;

/**
 * This is the model class for table "news".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $author
 * @property string $thumb
 * @property integer $status
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $clicks
 */
class News extends \yii\db\ActiveRecord
{
	
	const STATUS_ACTIVE = "1"; //已审核
	const STATUS_DISABLE = "0"; //未审核
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'content', 'author', 'create_at', 'update_at'], 'required'],
            [['content'], 'string'],
            [['status', 'create_at', 'update_at', 'clicks'], 'integer'],
            [['title', 'thumb'], 'string', 'max' => 255],
            [['author'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '内容',
            'author' => '发布人',
            'thumb' => '封面图片',
            'status' => '状态',
            'create_at' => '创建时间',
            'update_at' => '修改时间',
            'clicks' => '阅读量',
        ];
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
