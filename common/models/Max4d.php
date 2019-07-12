<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "max4d".
 *
 * @property int $id
 * @property int $ki
 * @property string $ngay
 * @property string $g1
 * @property string $g21
 * @property string $g22
 * @property string $g31
 * @property string $g32
 * @property string $g33
 * @property string $g4
 * @property string $g5
 * @property int $t1
 * @property int $t2
 * @property int $t3
 * @property int $t4
 * @property int $t5
 * @property int $th41
 * @property int $th42
 * @property int $th43
 * @property int $th61
 * @property int $th62
 * @property int $th63
 * @property int $th121
 * @property int $th122
 * @property int $th123
 * @property int $th241
 * @property int $th242
 * @property int $th243
 */
class Max4d extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'max4d';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ki', 'ngay', 'g1', 'g21', 'g22', 'g31', 'g32', 'g33', 'g4', 'g5', 't1', 't2', 't3', 't4', 't5', 'th41', 'th42', 'th43', 'th61', 'th62', 'th63', 'th121', 'th122', 'th123', 'th241', 'th242', 'th243'], 'required'],
            [['ki', 't1', 't2', 't3', 't4', 't5', 'th41', 'th42', 'th43', 'th61', 'th62', 'th63', 'th121', 'th122', 'th123', 'th241', 'th242', 'th243'], 'integer'],
            [['ngay'], 'safe'],
            [['g1', 'g21', 'g22', 'g31', 'g32', 'g33', 'g4', 'g5'], 'string', 'max' => 11],
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
            'g1' => 'G1',
            'g21' => 'G21',
            'g22' => 'G22',
            'g31' => 'G31',
            'g32' => 'G32',
            'g33' => 'G33',
            'g4' => 'G4',
            'g5' => 'G5',
            't1' => 'T1',
            't2' => 'T2',
            't3' => 'T3',
            't4' => 'T4',
            't5' => 'T5',
            'th41' => 'Th41',
            'th42' => 'Th42',
            'th43' => 'Th43',
            'th61' => 'Th61',
            'th62' => 'Th62',
            'th63' => 'Th63',
            'th121' => 'Th121',
            'th122' => 'Th122',
            'th123' => 'Th123',
            'th241' => 'Th241',
            'th242' => 'Th242',
            'th243' => 'Th243',
        ];
    }
}
