<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Procurement */

$this->title = '申请新项目';
$this->params['breadcrumbs'][] = ['label' => '项目列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="procurement-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
