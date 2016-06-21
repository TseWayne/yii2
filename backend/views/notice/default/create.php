<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\modules\notice\models\Notice */

$this->title = '创建公告';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notice-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
