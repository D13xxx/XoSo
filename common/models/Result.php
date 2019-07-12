<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "result".
 *
 * @property int $id
 * @property string $result
 * @property string $postdate
 * @property int $province
 * @property int $date
 */
class Result extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['result'], 'string'],
            [['postdate'], 'safe'],
            [['province', 'date'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'result' => 'Result',
            'postdate' => 'Postdate',
            'province' => 'Province',
            'date' => 'Date',
        ];
    }
    function getDatesFromRange($startDate, $endDate)
    {
        $return = array($startDate);
        $start = $startDate;
        $i=1;
        if (strtotime($startDate) < strtotime($endDate))
        {
            while (strtotime($start) < strtotime($endDate))
            {
                $start = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
                $return[] = $start;
                $i++;
            }
        }
        return $return;
    }
    function getSwitchDate($listDate){
        $nameDates = date("l",strtotime($listDate));
        $weekdays = strtolower($nameDates);
        switch ($weekdays) {
            case 'monday':
                $weekday = 'T.2';

                $mt = array(array(183, "py", "phu-yen", "Phú Yên", "py", 27), array(188, "tth", "thua-thien-hue", "Thừa Thiên Huế", "tth", 26));
                $mn = array(array(166, "hcm", "ho-chi-minh", "Hồ Chí Minh", "hcm", 1), array(160, "cm", "ca-mau", "Cà Mau", "cm", 3), array(164, "dt", "dong-thap", "Đồng Tháp", "dt", 2));
                break;
            case 'tuesday':
                $weekday = 'T.3';

                $mt = array(array(177, "dl", "dac-lac", "Đắc Lắc", "dlk", 29), array(186, "qn", "quang-nam", "Quảng Nam", "qnm", 28));
                $mn = array(array(155, "bt", "ben-tre", "Bến Tre", "btr", 7), array(174, "vt", "vung-tau", "Vũng Tàu", "vt", 8), array(120, "bl", "bac-lieu", "Bạc Liêu", "bl", 9));
                break;
            case 'wednesday':
                $weekday = 'T.4';
                $mt = array(array(180, "kh", "khanh-hoa", "Khánh Hòa", "kh", 31), array(176, "dn", "da-nang", "Đà nẵng", "dng", 30));
                $mn = array(array(163, "dn", "dong-nai", "Đồng Nai", "dn", 10), array(161, "ct", "can-tho", "Cần Thơ", "ct", 11), array(169, "st", "soc-trang", "Sóc Trăng", "st", 12));
                break;
            case 'thursday':
                $weekday = 'T.5';

                $mt = array(array(175, "bd", "binh-dinh", "Bình Định", "bdh", 32), array(184, "qb", "quang-binh", "Quảng Bình", "qb", 33), array(187, "qt", "quang-tri", "Quảng Trị", "qt", 34));
                $mn = array(array(156, "ag", "an-giang", "An Giang", "ag", 14), array(170, "tn", "tay-ninh", "Tây Ninh", "tn", 13), array(159, "bt", "binh-thuan", "Bình Thuận", "bth", 15));

                break;
            case 'friday':
                $weekday = 'T.6';

                $mt = array(array(179, "gl", "gia-lai", "Gia Lai", "gl", 35), array(182, "nt", "ninh-thuan", "Ninh Thuận", "nt", 36));
                $mn = array(array(157, "bd", "binh-duong", "Bình Dương", "bd", 17), array(172, "tv", "tra-vinh", "Trà Vinh", "tv", 18), array(173, "vl", "vinh-long", "Vĩnh Long", "vl", 16));

                break;
            case 'saturday':
                $weekday = 'T.7';

                $mt = array(array(176, "dn", "da-nang", "Đà Nẵng", "dng", 30), array(178, "dn", "dac-nong", "Đắc Nông", "dno", 38), array(185, "qn", "quang-ngai", "Quảng Ngãi", "qni", 37));
                $mn = array(array(166, "hcm", "ho-chi-minh", "Hồ Chí Minh", "hcm", 1), array(168, "la", "long-an", "Long An", "la", 19), array(158, "bp", "binh-phuoc", "Bình Phước", "bp", 21), array(165, "hg", "hau-giang", "Hậu Giang", "hg", 20));

                break;
            default:
                $weekday = 'CN';

                $mt = array(array(181, "kt", "kon-tum", "Kon Tum", "kt", 39), array(180, "kh", "khanh-hoa", "Khánh Hoà", "kh", 31));
                $mn = array(array(171, "tg", "tien-giang", "Tiền Giang", "tg", 22), array(167, "kg", "kien-giang", "Kiên Giang", "kg", 23), array(162, "dl", "da-lat", "Đà Lạt", "dl", 24));
                break;
        }
    }
    public function getProvinces()
    {
        return $this->hasOne(Province::className(), ['id' => 'province']);
    }
}
