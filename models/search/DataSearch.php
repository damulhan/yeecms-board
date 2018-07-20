<?php

namespace modules\board\models\search;

use modules\board\models\Data;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * CategorySearch represents the model behind the search form about `yeesoft\post\models\Category`.
 */
class DataSearch extends Data
{
    public $created_at_operand;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'board_id', 'display', 'hidden', 'created_by', 'updated_by'], 'integer'],
            [['name', 'bid', 'category'], 'string'],
            [['name', 'title', 'bid', 'board_id'], 'safe'],
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
		#debug('table', Data::tableName());
		
        $query = Data::find(); #->joinWith('translations');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 20),
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
        ]);

        $dataProvider->setSort([
            'attributes' => ArrayHelper::merge($dataProvider->sort->attributes, [
				'title' => [
                    'asc' => ['title' => SORT_ASC],
                    'desc' => ['title' => SORT_DESC],
                    'label' => '제목',
                    'value' => 'title',
                ],
            ]),
            'defaultOrder' => ['id' => SORT_DESC],
        ]);
		
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
			'board_id' => $this->board_id, 
			'bid' => $this->bid,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([($this->created_at_operand) ? $this->created_at_operand : '=', 'created_at', ($this->created_at) ? strtotime($this->created_at) : null]);

        $query
			->andFilterWhere(['like', 'name', $this->name])
			->andFilterWhere(['like', 'nic', $this->nic])
			->andFilterWhere(['like', 'category', $this->category])
			->andFilterWhere(['like', 'title', $this->title]);
 
        return $dataProvider;
    }
}