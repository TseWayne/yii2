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
class Signup extends Model
{
    public $username;
    public $email;
    public $password;
    public $name;
    public $telephone;
    public $roles;
    public $danwei;
    public $ke;
    public $id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => 'common\models\User', 'message' => '用户名已经存在.'],
            [['username','name'], 'string', 'min' => 2, 'max' => 255],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => 'common\models\User', 'message' => '邮件地址已经存在.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            
            ['roles', 'required'],

            
            [['telephone','name'], 'required'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'name' => '主体',
            'email' => 'E-Mail',
            'status' => '状态',
            'created_at' => '注册时间',
            'telephone' => '电话',
            'roles' => '用户角色',
            'danwei' => '预算单位',
            'ke' => '对口主管科',
            'password' => '密码'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->name = $this->name;
            $user->status = User::STATUS_ACTIVE;
            $user->telephone = $this->telephone;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            if ($user->save()) {
                Yii::$app->getAuthManager()->revokeAll($user->id);
                $items = is_array($this->roles)?$this->roles:[$this->roles];
                $assign = new Assignment($user->id);
                $assign->assign($items);
                
                return $user;
            }
        }

        return null;
    }

}
