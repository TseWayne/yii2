<?php

namespace backend\modules\procurement\components;

use yii\validators\Validator;

/**
 * ProcurementController implements the CRUD actions for Procurement model.
 */
class UploadPathCheckValidator extends Validator
{
    public function init()
    {
        parent::init();
    }
    
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (empty($value)) {
            $model->addError($attribute, '附件地址保存错误');
        }
    }
    
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = json_encode('上传附件之前请先选择采购方式。');
        return <<<JS
if (!$('#procurement-caigoufangshi').val()) {
    messages.push($message);
}
JS;
    }
}
