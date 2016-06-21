<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ProcurementSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="procurement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'danwei') ?>

    <?= $form->field($model, 'shixiang') ?>

    <?= $form->field($model, 'yaoqiu') ?>

    <?= $form->field($model, 'liyou') ?>

    <?php // echo $form->field($model, 'zonge') ?>

    <?php // echo $form->field($model, 'laiyuan_yusuan') ?>

    <?php // echo $form->field($model, 'laiyuan_feishui') ?>

    <?php // echo $form->field($model, 'laiyuan_shangji') ?>

    <?php // echo $form->field($model, 'laiyuan_qita') ?>

    <?php // echo $form->field($model, 'jingbanren') ?>

    <?php // echo $form->field($model, 'dianhua') ?>

    <?php // echo $form->field($model, 'starttime') ?>

    <?php // echo $form->field($model, 'edittime') ?>

    <?php // echo $form->field($model, 'userid') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'xiangmulei') ?>

    <?php // echo $form->field($model, 'caigoufangshi') ?>

    <?php // echo $form->field($model, 'sign_zhuguan') ?>

    <?php // echo $form->field($model, 'sign_zhuguan_is') ?>

    <?php // echo $form->field($model, 'sign_zhuguan_time') ?>

    <?php // echo $form->field($model, 'sign_jing') ?>

    <?php // echo $form->field($model, 'sign_jing_is') ?>

    <?php // echo $form->field($model, 'sign_jing_time') ?>

    <?php // echo $form->field($model, 'sign_fuze') ?>

    <?php // echo $form->field($model, 'sign_fuze_is') ?>

    <?php // echo $form->field($model, 'sign_fuze_time') ?>

    <?php // echo $form->field($model, 'endtime') ?>

    <?php // echo $form->field($model, 'daili') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
