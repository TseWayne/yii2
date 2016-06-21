<?php

namespace backend\modules\procurement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\procurement\models\Procurement;

/**
 * ProcurementSearch represents the model behind the search form about `app\models\Procurement`.
 */
class ProcurementSearch extends Procurement
{
    private $_status;
    private $_userid;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','xiangmulei','caigoufangshi','ke','daili_id', 'userid','is_extract'], 'integer'],
            [['telephone', 'jingbanren', 'daili_name','shixiang'], 'safe'],
            ['status', 'in', 'range' => array_keys(self::$STATUS)],
            [['zonge', 'laiyuan_yusuan', 'laiyuan_feishui', 'laiyuan_shangji', 'laiyuan_qita'], 'number'],
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
    
    public function loadStatus($s){
        if(empty($s)) $this->_status = 'noway-hahaha'; //load之后，默认查询不存在状态，显示空状态
        else $this->_status = $s;
    }
    
    public function loadUserid($s){
        if(empty($s))  $this->_userid = null; //默认显示全部
        else $this->_userid = $s;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Procurement::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'edittime' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if(!empty($this->_status))  
            $query->andFilterWhere(['status'=>$this->_status]);
        
        
        if(!empty($this->_userid))
            $query->andFilterWhere(['userid'=>$this->_userid]);
        
        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
            'id' => $this->id,
            'zonge' => $this->zonge,
            'xiangmulei' => $this->xiangmulei,
            'caigoufangshi' => $this->caigoufangshi,
            'ke' => $this->ke,
            'userid' => $this->userid,
        ]);

        $query->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'jingbanren', $this->jingbanren])
            ->andFilterWhere(['like', 'daili_name', $this->daili_name]);

        //echo("<p style='margin-left:20%'>".$query->createCommand()->getRawSql()."</p>");
        return $dataProvider;
    }
}
