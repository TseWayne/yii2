<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Procurement */

$this->title = '采购项目修改: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '项目ID:'.$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="procurement-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
