<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use mdm\admin\components\Helper;

/* @var $this yii\web\View */
/* @var $model mdm\admin\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$controllerId = $this->context->uniqueId . '/';
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php
        if ($model->status == 0 && Helper::checkRoute($controllerId . 'activate')) {
            echo Html::a('激活', ['activate', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => Yii::t('rbac-admin', 'Are you sure you want to activate this user?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
        <?php
        if (Helper::checkRoute($controllerId . 'update')) {
            echo Html::a('修改', ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary',
            ]);
        }
        ?>
        <?php
        if (Helper::checkRoute($controllerId . 'delete')) {
            echo Html::a('删除', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]);
        }
        ?>
        <?php
        if (Helper::checkRoute($controllerId . 'signup')) {
            echo Html::a('添加', ['signup'], [
                'class' => 'btn btn-success',
            ]);
        }
        ?>
    </p>

    <?php
    $roles = '';
    foreach(Yii::$app->authManager->getRolesByUser($model->id) as $k=>$v){
        $roles .= Html::tag('span',$v->description,['class'=>'label label-primary']).' ';
    }
    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => '用户角色',
                'value' => $roles,
                'format' => 'html'
            ],
            'username',
            'name',
            'email:email',
            'telephone',
            'created_at:date',
            'status',
        ],
    ])
    ?>

</div>
