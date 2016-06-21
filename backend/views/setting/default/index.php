<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use funson86\setting\models\Setting;
use funson86\setting\Module;

$this->title = Module::t('setting', 'Setting');
$this->params['breadcrumbs'][] = $this->title;

$items = [];
foreach($settingParent as $parent)
{
    $item['label'] = Module::t('setting', $parent->code);

    $str = '';
    $children = Setting::find()->where(['parent_id' => $parent->id])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC])->all();
    foreach($children as $child)
    {
        $str .= '<div class="form-group"><label class="control-label" for="blogcatalog-parent_id">' . Module::t('setting', $child->code) . '</label>';
        if($child->type == 'boolean'){
            if($child->value) $ison = true;
            else $isoff = true;
            $str .= '<div class="radio"><label class="radio-inline">';
            $str .= Html::radio("Setting[$child->code]", $isoff, ["value"=>0])." 不需要 ";
            $str .= '</label><label class="radio-inline">';
            $str .= Html::radio("Setting[$child->code]", $ison, ["value"=>1])." 需要";
            $str .= '</label></div>';
        }elseif($child->type == 'text')
            $str .= Html::textInput("Setting[$child->code]", $child->value, ["class" => "form-control"]);
        elseif($child->type == 'textarea')
            $str .= Html::textarea("Setting[$child->code]", $child->value, ["class" => "form-control","rows"=>3]);
        elseif($child->type == 'password')
            $str .= Html::passwordInput("Setting[$child->code]", $child->value, ["class" => "form-control"]);
        elseif($child->type == 'select') {
            $options = [];
            $arrayOptions = explode(',', $child->store_range);
            foreach($arrayOptions as $option)
                $options[$option] = Module::t('setting', $option);

            $str .= Html::dropDownList("Setting[$child->code]", $child->value, $options, ["class" => "form-control"]);
        }

        $str .= '</div>';
    }
    $item['content'] = $str;

    array_push($items, $item);
}
?>

<div class="setting-form">
    <?php $form = ActiveForm::begin(); ?>

    <?php
    echo \yii\bootstrap\Tabs::widget([
        'items' => $items,
        'options' => ['tag' => 'div'],
        'itemOptions' => ['tag' => 'div'],
        'clientOptions' => ['collapsible' => false],
    ]);
    ?>
    
    <div class="form-group">
        <?= Html::submitButton(Module::t('setting', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
