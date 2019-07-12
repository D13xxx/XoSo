<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "phan_tich".
 *
 * @property int $ID
 * @property string $Title
 * @property string $Description
 * @property string $Thumbnail
 * @property string $Content
 * @property int $Type
 * @property string $Date
 * @property string $CreateDate
 * @property string $UpdateDate
 */
class PhanTich extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    const MIENBAC     = 1;
    const MIENTRUNG = 2;
    const MIENNAM = 3;

    public static function MIEN_ARRAY()
    {
        return [
            self::MIENBAC => 'Miền Bắc',
            self::MIENTRUNG => 'Miền Trung',
            self::MIENNAM => 'Miền Nam',
        ];
    }
    public static function MIEN_COLOR_ARRAY()
    {
        return [
            self::MIENBAC => 'default',
            self::MIENTRUNG => 'primary',
            self::MIENNAM => 'info',
        ];
    }
    public static function tableName()
    {
        return 'phan_tich';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['Title', 'Description', 'Content', 'Type', 'Date'], 'required'],
            [['Content'], 'string'],
            [['Type'], 'integer'],
            [['Date', 'CreateDate', 'UpdateDate'], 'safe'],
            [['Title', 'Description', 'Thumbnail'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'Title' => 'Title',
            'Description' => 'Description',
            'Thumbnail' => 'Thumbnail',
            'Content' => 'Content',
            'Type' => 'Type',
            'Date' => 'Date',
            'CreateDate' => 'Create Date',
            'UpdateDate' => 'Update Date',
        ];
    }
}
