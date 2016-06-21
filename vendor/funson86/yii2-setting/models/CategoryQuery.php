<?php
namespace funson86\setting\models;

use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Category Query
 */
class CategoryQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}
