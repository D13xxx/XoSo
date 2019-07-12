<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "kinh_nghiem".
 *
 * @property int $id
 * @property string $title
 * @property string $thumbnail
 * @property string $description
 * @property string $content
 * @property int $order_number
 * @property int $public
 */
class KinhNghiem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kinh_nghiem';
    }

    const VISIBLE = 1;
    const ACTIVE = 0;

    public static function KN_ARRAY()
    {
        return [
            self::ACTIVE => 'Public',
            self::VISIBLE => 'Visible',
        ];
    }
    public static function KN_COLOR_ARRAY()
    {
        return [
            self::ACTIVE => 'default',
            self::VISIBLE => 'primary',
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description', 'content', 'order_number', 'public'], 'required'],
            [['content'], 'string'],
            [['order_number', 'public'], 'integer'],
            [['title', 'thumbnail', 'description'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'thumbnail' => 'Thumbnail',
            'description' => 'Description',
            'content' => 'Content',
            'order_number' => 'Order Number',
            'public' => 'Public',
        ];
    }
}
