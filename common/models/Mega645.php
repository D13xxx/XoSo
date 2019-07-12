<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mega645".
 *
 * @property int $id
 * @property int $ki
 * @property string $ngay
 * @property string $jackport
 * @property int $so1
 * @property int $so2
 * @property int $so3
 * @property int $so4
 * @property int $so5
 * @property int $so6
 * @property int $g0
 * @property int $g1
 * @property int $g2
 * @property int $g3
 */
class Mega645 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mega645';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ki', 'ngay', 'jackport', 'so1', 'so2', 'so3', 'so4', 'so5', 'so6', 'g0', 'g1', 'g2', 'g3'], 'required'],
            [['ki', 'jackport', 'so1', 'so2', 'so3', 'so4', 'so5', 'so6', 'g0', 'g1', 'g2', 'g3'], 'integer'],
            [['ngay'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ki' => 'Kì',
            'ngay' => 'Ngày',
            'jackport' => 'Jackport',
            'so1' => 'So1',
            'so2' => 'So2',
            'so3' => 'So3',
            'so4' => 'So4',
            'so5' => 'So5',
            'so6' => 'So6',
            'g0' => 'G0',
            'g1' => 'G1',
            'g2' => 'G2',
            'g3' => 'G3',
        ];
    }
}
