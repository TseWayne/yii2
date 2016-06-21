<?php

namespace backend\modules\procurement\models;

use Yii;

/**
 * This is the model class for table "action_log".
 *
 * @property integer $id
 * @property integer $pid
 * @property integer $userid
 * @property integer $time
 * @property string $ip
 * @property string $route
 * @property string $memo
 */
class ActionLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'action_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'userid', 'time', 'memo'], 'required'],
            [['pid', 'userid', 'time','pis'], 'integer'],
            [['memo'], 'string'],
            [['ip','pstatus'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '项目ID',
            'userid' => '用户',
            'time' => '操作时间',
            'ip' => 'Ip',
            'route' => '路由',
            'memo' => '内容',
        ];
    }
}
