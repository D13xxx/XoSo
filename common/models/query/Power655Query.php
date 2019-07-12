<?php

namespace common\models\query;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Power655;

/**
 * Power655Query represents the model behind the search form about `common\models\Power655`.
 */
class Power655Query extends Power655
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ki', 'jackport1', 'jackport2', 'so1', 'so2', 'so3', 'so4', 'so5', 'so6', 'so7', 'j1', 'j2', 'g1', 'g2', 'g3'], 'integer'],
            [['ngay'], 'safe'],
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
        $query = Power655::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ki' => $this->ki,
            'ngay' => $this->ngay,
            'jackport1' => $this->jackport1,
            'jackport2' => $this->jackport2,
            'so1' => $this->so1,
            'so2' => $this->so2,
            'so3' => $this->so3,
            'so4' => $this->so4,
            'so5' => $this->so5,
            'so6' => $this->so6,
            'so7' => $this->so7,
            'j1' => $this->j1,
            'j2' => $this->j2,
            'g1' => $this->g1,
            'g2' => $this->g2,
            'g3' => $this->g3,
        ]);

        return $dataProvider;
    }
}
