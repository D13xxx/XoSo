<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "province".
 *
 * @property int $id
 * @property string $category
 * @property int $parent
 * @property int $stt
 * @property string $status
 * @property int $stthome
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category'], 'required'],
            [['parent', 'stt', 'stthome'], 'integer'],
            [['status'], 'string'],
            [['category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category' => 'Category',
            'parent' => 'Parent',
            'stt' => 'Stt',
            'status' => 'Status',
            'stthome' => 'Stthome',
        ];
    }
    public function getCategory()
    {
        return $this->hasMany(Province::className(), ['parent' => 'category']);
    }
}
