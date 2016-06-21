<?php
namespace funson86\setting\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Category Search Model
 *
 * @property integer $id
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property string $name
 */
class CategorySearch extends Category
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name','safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    
    public function search($params)
    {
        
        $query = Category::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        
        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->andFilterWhere(['like', 'name', $this->name]);
        
        if(is_numeric($params[pid])){
            $query->children($params[pid],1)->all();
        }else{
            $query->roots()->all();
        }

        return $dataProvider;
    }
}
