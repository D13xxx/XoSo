<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "power655".
 *
 * @property int $id
 * @property int $ki
 * @property string $ngay
 * @property string $jackport1
 * @property string $jackport2
 * @property int $so1
 * @property int $so2
 * @property int $so3
 * @property int $so4
 * @property int $so5
 * @property int $so6
 * @property int $so7
 * @property int $j1
 * @property int $j2
 * @property int $g1
 * @property int $g2
 * @property int $g3
 */
class Power655 extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'power655';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'ki', 'ngay', 'jackport1', 'jackport2', 'so1', 'so2', 'so3', 'so4', 'so5', 'so6', 'so7', 'j1', 'j2', 'g1', 'g2', 'g3'], 'required'],
            [['id', 'ki', 'jackport1', 'jackport2', 'so1', 'so2', 'so3', 'so4', 'so5', 'so6', 'so7', 'j1', 'j2', 'g1', 'g2', 'g3'], 'integer'],
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
            'jackport1' => 'Jackport1',
            'jackport2' => 'Jackport2',
            'so1' => 'So1',
            'so2' => 'So2',
            'so3' => 'So3',
            'so4' => 'So4',
            'so5' => 'So5',
            'so6' => 'So6',
            'so7' => 'So7',
            'j1' => 'J1',
            'j2' => 'J2',
            'g1' => 'G1',
            'g2' => 'G2',
            'g3' => 'G3',
        ];
    }
}
