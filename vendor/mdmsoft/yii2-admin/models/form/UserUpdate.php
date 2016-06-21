<?php
namespace mdm\admin\models\form;

use Yii;
use common\models\User;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use mdm\admin\models\Assignment;

/**
 * Signup form
 */
class UserUpdate extends User
{

    public $newpassword;
    public $roles;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'common\models\User', 'message' => '用户名已经存在.', 'filter' => ['<>','id',$this->id]],
            [['username','name'], 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'common\models\User', 'message' => '邮件地址已经存在.', 'filter' => ['<>','email',$this->email]],

            ['newpassword', 'string', 'min' => 6],
            
            ['roles', 'required'],
            
            [['telephone','name'], 'required'],
        ];
    }
    
    
    public function doupdate(){
        if($this->validate()){
            if(!empty($this->newpassword)){
                $this->setPassword($this->newpassword);
                $this->generateAuthKey();
            }
            
            if ($this->save()) {
                Yii::$app->getAuthManager()->revokeAll($this->id);
                $items = is_array($this->roles)?$this->roles:[$this->roles];
                $assign = new Assignment($this->id);
                $assign->assign($items);
                
                return true;
            }
        }
        return false;
    }

}
