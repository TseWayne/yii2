<?php
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \zhuravljov\yii\rest\models\ImportForm $model
 */


$this->title = '导入';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rest-collection-import">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <?= $this->render('_import-form', [
                'model' => $model,
            ]) ?>
        </div>
    </div>
</div>