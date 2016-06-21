<?php

namespace backend\modules\notice\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\notice\models\Notice;


/**
 * NoticeSearch represents the model behind the search form about `backend\modules\notice\models\Notice`.
 */
class NoticeSearch extends Notice
{
	public $shixiang;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'cgId', 'create_at', 'update_at', 'clicks','status'], 'integer'],
            [['title', 'content','shixiang','author'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Notice::find();
        
        $query->joinWith(['procurement']);
		//$query->where('notice.status > 0');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        
        $dataProvider->setSort([
        	'attributes' => [
        		'shixiang' => [
	        		'asc' => ['procurement.shixiang' => SORT_ASC],
	        		'desc' => ['procurement.shixiang'=>SORT_DESC]
        		],
        		'id' => [
	        		'asc' => ['notice.id' => SORT_ASC],
	        		'desc' => ['notice.id'=>SORT_DESC]
        		],
        		'title' => [
	        		'asc' => ['notice.title' => SORT_ASC],
	        		'desc' => ['notice.title'=>SORT_DESC]
        		],
        		'author' => [
	        		'asc' => ['notice.author' => SORT_ASC],
	        		'desc' => ['notice.author'=>SORT_DESC]
        		],
        		
        
        	]
        ]);
        

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'notice.id' => $this->id,
            'notice.create_at' => $this->create_at,
            'notice.update_at' => $this->update_at,
            'notice.clicks' => $this->clicks,
        ]);

        $query->andFilterWhere(['like', 'notice.title', $this->title])
            ->andFilterWhere(['like', 'notice.content', $this->content])
            ->andFilterWhere(['like', 'notice.author', $this->author])
            ->andFilterWhere(['like', 'notice.status', $this->status]);
        
        $query->andFilterWhere(['like', 'procurement.shixiang', $this->shixiang]) ;

        return $dataProvider;
    }
}
