<?php

namespace common\models\query;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Mega645;

/**
 * Mega645Query represents the model behind the search form about `common\models\Mega645`.
 */
class Mega645Query extends Mega645
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ki', 'jackport', 'so1', 'so2', 'so3', 'so4', 'so5', 'so6', 'g0', 'g1', 'g2', 'g3'], 'integer'],
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
        $query = Mega645::find();

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
            'jackport' => $this->jackport,
            'so1' => $this->so1,
            'so2' => $this->so2,
            'so3' => $this->so3,
            'so4' => $this->so4,
            'so5' => $this->so5,
            'so6' => $this->so6,
            'g0' => $this->g0,
            'g1' => $this->g1,
            'g2' => $this->g2,
            'g3' => $this->g3,
        ]);

        return $dataProvider;
    }
}
