<?php

namespace common\models\Item;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Item\Item;

/**
 * ItemSearch represents the model behind the search form about `common\models\Item\Item`.
 */
class ItemSearch extends Item
{

    public $globalSearch;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'price', 'category_id'], 'integer'],
            [['name', 'globalSearch'], 'safe'],
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
        $query = Item::find();

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

        // grid filtering conditions
        // $query->andFilterWhere([
        //     'id' => $this->id,
        //     'price' => $this->price,
        //     'category_id' => $this->category_id,
        // ]);

        $query->andFilterWhere(['like', 'name', $this->globalSearch]);

        return $dataProvider;
    }
}
