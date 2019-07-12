<?php

namespace common\models\query;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Max4d;

/**
 * Max4dQuery represents the model behind the search form about `common\models\Max4d`.
 */
class Max4dQuery extends Max4d
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ki', 't1', 't2', 't3', 't4', 't5', 'th41', 'th42', 'th43', 'th61', 'th62', 'th63', 'th121', 'th122', 'th123', 'th241', 'th242', 'th243'], 'integer'],
            [['ngay', 'g1', 'g21', 'g22', 'g31', 'g32', 'g33', 'g4', 'g5'], 'safe'],
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
        $query = Max4d::find();

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
            't1' => $this->t1,
            't2' => $this->t2,
            't3' => $this->t3,
            't4' => $this->t4,
            't5' => $this->t5,
            'th41' => $this->th41,
            'th42' => $this->th42,
            'th43' => $this->th43,
            'th61' => $this->th61,
            'th62' => $this->th62,
            'th63' => $this->th63,
            'th121' => $this->th121,
            'th122' => $this->th122,
            'th123' => $this->th123,
            'th241' => $this->th241,
            'th242' => $this->th242,
            'th243' => $this->th243,
        ]);

        $query->andFilterWhere(['like', 'g1', $this->g1])
            ->andFilterWhere(['like', 'g21', $this->g21])
            ->andFilterWhere(['like', 'g22', $this->g22])
            ->andFilterWhere(['like', 'g31', $this->g31])
            ->andFilterWhere(['like', 'g32', $this->g32])
            ->andFilterWhere(['like', 'g33', $this->g33])
            ->andFilterWhere(['like', 'g4', $this->g4])
            ->andFilterWhere(['like', 'g5', $this->g5]);

        return $dataProvider;
    }
}
