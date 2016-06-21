<?php
namespace frontend\modules\v1\controllers;

use yii\rest\ActiveController;
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;


/**
 * Site controller
 */
class UserController extends ActiveController implements Linkable
{
    public $modelClass = 'common\models\User';
    
    public function fields()
    {
        $fields = parent::fields();
        // remove fields that contain sensitive information
        unset($fields['auth_key'], $fields['password_hash'], $fields['password_reset_token']);
        return $fields;
    }
    
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user', 'id' => $this->id], true),
        ];
    }
    
}
