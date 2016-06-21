<?php

namespace backend\modules\procurement\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\procurement\models\ActionLog;
use common\models\User;

/**
 * ActionLogSearch represents the model behind the search form about `backend\modules\procurement\models\ActionLog`.
 */
class ActionLogSearch extends ActionLog
{
    /**
     * @inheritdoc
     */
    public $username;
    private $_userid;
    private $_pid;
    
    public function rules()
    {
        return [
            [['ip', 'memo', 'username'], 'safe'],
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
    
    public function loadPid($s){
        if(!empty($s)) $this->_pid = $s;
    }
    
    public function loadUserid($s){
        if(empty($s))  $this->_userid = null;
        else $this->_userid = $s;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params,$id=null)
    {
        $query = ActionLog::find();
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'time' => [
                        'asc' => ['time' => SORT_ASC],
                        'desc' => ['time' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => [
                    'time' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if(!empty($this->_pid))
            $query->andFilterWhere(['pid'=>$this->_pid]);
        
        if(!empty($this->_userid))
            $query->andFilterWhere(['userid'=>$this->_userid]);
        
        $query->andFilterWhere([
            'userid'=>User::findOne(['username'=>$this->username])->id,
        ]);
            
        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'memo', $this->memo]);

        //echo("<p style='margin-left:20%'>".$query->createCommand()->getRawSql()."</p>");
        return $dataProvider;
    }
}
