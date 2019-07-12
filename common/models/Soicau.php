<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "soicau".
 *
 * @property int $id
 * @property string $data
 * @property string $date
 * @property int $province
 * @property string $create_date
 */
class Soicau extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'soicau';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data', 'date', 'province', 'create_date'], 'required'],
            [[ 'province'], 'integer'],
            [['data'], 'string'],
            [['date', 'create_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'data' => 'Data',
            'date' => 'Date',
            'province' => 'Province',
            'create_date' => 'Create Date',
        ];
    }
}
