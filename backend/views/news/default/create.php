<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\news\models\News */

$this->title = '添加';
$this->params['breadcrumbs'][] = ['label' => '政务动态', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zwdt-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
