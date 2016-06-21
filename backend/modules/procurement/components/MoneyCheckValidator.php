<?php

namespace backend\modules\procurement\components;

use Yii;
use yii\validators\Validator;

/**
 * ProcurementController implements the CRUD actions for Procurement model.
 */
class MoneyCheckValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = "申报理由必须填写";
    }
    
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (empty($value) && ((int)$model->zonge)>=Yii::$app->setting->get('money')) {
            $model->addError($attribute, $this->message);
        }
    }
    
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = json_encode($this->message);
        return <<<JS
if (!$('#procurement-liyou').val() && parseInt($('#procurement-zonge').val()) >= settingMoney) {messages.push($message);}
JS;
    }
}
