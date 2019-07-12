<?php

namespace common\models\query;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Province;

/**
 * ProvinceQuery represents the model behind the search form about `common\models\Province`.
 */
class ProvinceQuery extends Province
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent', 'stt', 'stthome'], 'integer'],
            [['category', 'status'], 'safe'],
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
        $query = Province::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent' => $this->parent,
            'stt' => $this->stt,
            'stthome' => $this->stthome,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
