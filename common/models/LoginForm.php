<?php
namespace common\models;

use Yii;
use yii\base\Model;
use backend\modules\procurement\models\ActionLog;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
            'role' => '用户角色',
            'danwei' => '预算单位',
            'rememberMe' => '记住账号',
            'password' => '密码'
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '账号或者密码错误.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $r = Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            $this->inserLog($r);
            return $r;
        } else {
            $this->inserLog(false);
            return false;
        }
    }
    
    
    public static function logout(){
        $log = new ActionLog();
        $log->userid = Yii::$app->user->id;
        $log->pid = 0;
        $log->time = time();
        $log->ip = Yii::$app->request->userIP;
        $log->memo = '用户('.Yii::$app->user->identity->username.')退出了登陆。';
        $log->pis = 0;
        $log->pstatus = '';
        Yii::$app->user->logout();
        $log->save();
    }
    
    
    public function inserLog($insert){
        $log = new ActionLog();
        $log->userid = Yii::$app->user->isGuest?0:Yii::$app->user->id;
        $log->pid = 0;
        $log->time = time();
        $log->ip = Yii::$app->request->userIP;
        $log->memo = "用户(".$this->username.")尝试登陆结果：". ($insert?'成功':'失败')."。";
        $log->pis = 0;
        $log->pstatus = '';
        $log->save();
    }


    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}
