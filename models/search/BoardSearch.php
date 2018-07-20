<?php

namespace modules\board\models\search;

use modules\board\models\Board;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CategorySearch represents the model behind the search form about `yeesoft\post\models\Category`.
 */
class BoardSearch extends Board
{
    public $created_at_operand;
	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'group_id', 'created_by', 'updated_by'], 'integer'],
            [['name', 'category', 'skin', 'm_skin'], 'string'],
            [['created_at_operand', 'name', 'created_at', 'updated_at'], 'safe'],
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
        $query = Board::find(); #->joinWith('translations');

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

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([($this->created_at_operand) ? $this->created_at_operand : '=', 'created_at', ($this->created_at) ? strtotime($this->created_at) : null]);
			
        $query
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}