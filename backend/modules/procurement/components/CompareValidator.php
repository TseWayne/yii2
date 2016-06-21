<?php

namespace backend\modules\procurement\components;

use Yii;
use yii\validators\Validator;

/**
 * ProcurementController implements the CRUD actions for Procurement model.
 */
class CompareValidator extends Validator
{
    public function init()
    {
        parent::init();
        $this->message = "资金来源总和必须等于预算总额";
    }
    
    public function validateAttribute($model, $attribute)
    {
        //'laiyuan_yusuan', 'laiyuan_feishui', 'laiyuan_shangji', 'laiyuan_qita'
        $value = $model->laiyuan_yusuan + $model->laiyuan_feishui + $model->laiyuan_shangji + $model->laiyuan_qita;
        $zonge = $model->zonge;
        if ($zonge != $value) {
            $model->addError($attribute, $this->message);
        }
    }
    
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = json_encode($this->message);
        return <<<JS
var zonge=parseInt($('#procurement-zonge').val());var value=parseInt($('#procurement-laiyuan_yusuan').val())+parseInt($('#procurement-laiyuan_feishui').val())+parseInt($('#procurement-laiyuan_shangji').val())+parseInt($('#procurement-laiyuan_qita').val());
if (zonge != value) {messages.push($message);}
JS;
    }
}
