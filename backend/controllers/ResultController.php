<?php

namespace backend\controllers;

use common\models\Max4d;
use common\models\Mega645;
use common\models\Power655;
use common\models\query\Max4dQuery;
use common\models\query\Mega645Query;
use common\models\query\Power655Query;
use common\models\Soicau;
use function GuzzleHttp\Psr7\str;
use Yii;
use common\models\Result;
use common\models\query\ResultQuery;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use DiDom\Element;
use DOMElement;
use yii\helpers\Json;
use common\models\DungChung;
use DiDom\Document;
use DiDom\Query;

/**
 * ResultController implements the CRUD actions for Result model.
 */
class ResultController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
         return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['ket-qua-xo-so', 'max4d', 'vietlott655','mega645'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['mega645', 'vietlott655','max4d','ket-qua-xo-so','soi-cau-mien-bac'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout','mega645', 'vietlott655','max4d','ket-qua-xo-so'],
                        'roles' => ['@'],
                    ],
                ],
            ],
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



    public function actionKetQuaXoSo($dateToDate = null)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $dateToDate = Yii::$app->request->get('dateToDate');
        $from_date = Yii::$app->request->get('from_date');
        $to_date = Yii::$app->request->get('to_date');
        $dateNow = date('Y-m-d');

        if ($dateToDate != null || $dateToDate != ''){
            $listDates = ResultController::getDatesFromRange($from_date,$to_date);
            foreach ($listDates as $listDate){
                $nameDate = date("l",strtotime($listDate));
                $weekday = strtolower($nameDate);
                // nếu mà miền bắc
                $url = 'http://ketqua.vn/kqxsmb-ket-qua-xo-so-mien-bac-ngay-'.date('d-m-Y',strtotime($listDate));

                if ($listDate < $dateNow){
                    // get data crawler
                    //mien bac
                    $document = new Document();
                    $document->loadHtmlFile($url);
                    $input = [];
                    $input[0] = $document->find('td')[39];
                    $input[1] = $document->find('td')[41];
                    $input[2] = $document->find('td')[43];
                    $input[3] = $document->find('td')[44];
                    $input[4] = $document->find('td')[46];
                    $input[5] = $document->find('td')[47];
                    $input[6] = $document->find('td')[48];
                    $input[7] = $document->find('td')[49];
                    $input[8] = $document->find('td')[50];
                    $input[9] = $document->find('td')[51];
                    $input[10] = $document->find('td')[53];
                    $input[11] = $document->find('td')[54];
                    $input[12] = $document->find('td')[55];
                    $input[13] = $document->find('td')[56];
                    $input[14] = $document->find('td')[58];
                    $input[15] = $document->find('td')[59];
                    $input[16] = $document->find('td')[60];
                    $input[17] = $document->find('td')[61];
                    $input[18] = $document->find('td')[62];
                    $input[19] = $document->find('td')[63];
                    $input[20] = $document->find('td')[65];
                    $input[21] = $document->find('td')[66];
                    $input[22] = $document->find('td')[67];
                    $input[23] = $document->find('td')[69];
                    $input[24] = $document->find('td')[70];
                    $input[25] = $document->find('td')[71];
                    $input[26] = $document->find('td')[72];

                    // quy chuẩn lưu data text
                    $str = "";
                    $str .= $input[0] . '|,';
                    $str .= $input[1] . '|,';
                    $str .= $input[2] . ',' . $input[3] . '|,';
                    $str .= $input[4] . ',' . $input[5] . ',' . $input[6] . ',' . $input[7] . ',' . $input[8] . ',' . $input[9] . '|,';
                    $str .= $input[10] . ',' . $input[11] . ',' . $input[12] . ',' . $input[13] . '|,';
                    $str .= $input[14] . ',' . $input[15] . ',' . $input[16] . ',' . $input[17] . ',' . $input[18] . ',' . $input[19] . '|,';
                    $str .= $input[20] . ',' . $input[21] . ',' . $input[22] . '|,';
                    $str .= $input[23] . ',' . $input[24] . ',' . $input[25] . ',' . $input[26];
                    $stripTagStr = strip_tags(trim(preg_replace('/[\t\s]/', '', $str)));
//
//                // check trong result co du lieu chua
                    $checkXoSoMienBac = Result::find()->where(['postdate'=>$listDate])->andWhere(['province'=>129])->count();
                    if($checkXoSoMienBac > 0){
//                        // update data
                       $model = Result::find()->where(['postdate'=>$listDate])->andWhere(['province'=>129])->one();
                       $model->postdate = $listDate;
                       $model->result = $stripTagStr;
                       $model->province = 129;
                       if ($model->save()) {
                           $productjson = Json::encode($model);
                           $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-bac.json');
                           $fp = fopen($jsonfile, 'w+');
                           fwrite($fp, $productjson);
                           fclose($fp);
                       }
                    }else{
                        // create data
                        $model = new Result();
                        $model->postdate = $listDate;
                        $model->result = $stripTagStr;
                        $model->province = 129;
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-bac.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }

                    // miền trung
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

                    $i = 0;
                    foreach ($mt as $maTinh) {
                        $urlMienTrung = "http://ketqua.vn/kqxs$maTinh[1]-ket-qua-xo-so-$maTinh[2]-ngay-".date('d-m-Y',strtotime($listDate));
                        $document = new Document();
                        $document->loadHtmlFile($urlMienTrung);
                        $input = [];
                        $input[$i][0] = $document->find('td')[42];
                        $input[$i][1] = $document->find('td')[44];
                        $input[$i][2] = $document->find('td')[46];
                        $input[$i][3] = $document->find('td')[48];
                        $input[$i][4] = $document->find('td')[49];
                        $input[$i][5] = $document->find('td')[51];
                        $input[$i][6] = $document->find('td')[52];
                        $input[$i][7] = $document->find('td')[53];
                        $input[$i][8] = $document->find('td')[54];
                        $input[$i][9] = $document->find('td')[55];
                        $input[$i][10] = $document->find('td')[56];
                        $input[$i][11] = $document->find('td')[57];
                        $input[$i][12] = $document->find('td')[59];
                        $input[$i][13] = $document->find('td')[61];
                        $input[$i][14] = $document->find('td')[62];
                        $input[$i][15] = $document->find('td')[63];
                        $input[$i][16] = $document->find('td')[65];
                        $input[$i][17] = $document->find('td')[67];

                        // quy chuẩn lưu data text
                        $str = "";
                        $str .= $input[$i][0] . "|,";
                        $str .= $input[$i][1] . "|,";
                        $str .= $input[$i][2] . "|,";
                        $str .= $input[$i][3] . ',' . $input[$i][4] . "|,";
                        $str .= $input[$i][9] . ',' . $input[$i][10] . ',' . $input[$i][11] . ',' . $input[$i][5] . ',' . $input[$i][6] . ',' . $input[$i][7] . ',' . $input[$i][8] . "|,";
                        $str .= $input[$i][12] . "|,";
                        $str .= $input[$i][13] . ',' . $input[$i][14] . ',' . $input[$i][15] . "|,";
                        $str .= $input[$i][16] . "|,";
                        $str .= $input[$i][17];
                        $stripTagStr = strip_tags(trim(preg_replace('/[\t\s]/', '', $str)));
                        $i++;
                        // check ngày tồn tại
                        $date = Result::find()
                            ->andWhere(['postdate' => $listDate])
                            ->andWhere(['province' => $maTinh[0]])->count();
                        if ($date > 0) {
                           $model = Result::find()
                               ->andWhere(['postdate' => $listDate])
                               ->andWhere(['province' => $maTinh[0]])->one();
                           $model->postdate =$listDate;
                           $model->result = $stripTagStr;
                           $model->province = $maTinh[0];
                           if ($model->save()) {
                               $productjson = Json::encode($model);
                               $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-trung.json');
                               $fp = fopen($jsonfile, 'w+');
                               fwrite($fp, $productjson);
                               fclose($fp);
                           }

                        } else {
                            // new Result
                            $model = new Result();
                            $model->postdate = $listDate;
                            $model->result = $stripTagStr;
                            $model->province = $maTinh[0];
                            if ($model->save()) {
                                $productjson = Json::encode($model);
                                $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-trung.json');
                                $fp = fopen($jsonfile, 'w+');
                                fwrite($fp, $productjson);
                                fclose($fp);
                            }
                        }
                    }
                    // mieen nam
                    foreach ($mn as $maTinh) {
                        $urlMienNam = "http://ketqua.vn/kqxs$maTinh[1]-ket-qua-xo-so-$maTinh[2]-ngay-".date('d-m-Y',strtotime($listDate));
                        $document = new Document();
                        $document->loadHtmlFile($urlMienNam);
                        $input = [];
                        $input[$i][0] = $document->find('td')[42];
                        $input[$i][1] = $document->find('td')[44];
                        $input[$i][2] = $document->find('td')[46];
                        $input[$i][3] = $document->find('td')[48];
                        $input[$i][4] = $document->find('td')[49];
                        $input[$i][5] = $document->find('td')[51];
                        $input[$i][6] = $document->find('td')[52];
                        $input[$i][7] = $document->find('td')[53];
                        $input[$i][8] = $document->find('td')[54];
                        $input[$i][9] = $document->find('td')[55];
                        $input[$i][10] = $document->find('td')[56];
                        $input[$i][11] = $document->find('td')[57];
                        $input[$i][12] = $document->find('td')[59];
                        $input[$i][13] = $document->find('td')[61];
                        $input[$i][14] = $document->find('td')[62];
                        $input[$i][15] = $document->find('td')[63];
                        $input[$i][16] = $document->find('td')[65];
                        $input[$i][17] = $document->find('td')[67];

                        // quy chuẩn lưu data text
                        $str = "";
                        $str .= $input[$i][0] . "|,";
                        $str .= $input[$i][1] . "|,";
                        $str .= $input[$i][2] . "|,";
                        $str .= $input[$i][3] . ',' . $input[$i][4] . "|,";
                        $str .= $input[$i][9] . ',' . $input[$i][10] . ',' . $input[$i][11] . ',' . $input[$i][5] . ',' . $input[$i][6] . ',' . $input[$i][7] . ',' . $input[$i][8] . "|,";
                        $str .= $input[$i][12] . "|,";
                        $str .= $input[$i][13] . ',' . $input[$i][14] . ',' . $input[$i][15] . "|,";
                        $str .= $input[$i][16] . "|,";
                        $str .= $input[$i][17];
                        $stripTagStr = strip_tags(trim(preg_replace('/[\t\s]/', '', $str)));
                        $i++;
                        // check ngày tồn tại
                        $date = Result::find()
                            ->andWhere(['postdate' => $listDate])
                            ->andWhere(['province' => $maTinh[0]])->count();
                        if ($date > 0) {
                           $model = Result::find()
                               ->andWhere(['postdate' => $listDate])
                               ->andWhere(['province' => $maTinh[0]])->one();
                           $model->postdate =$listDate;
                           $model->result = $stripTagStr;
                           $model->province = $maTinh[0];
                           if ($model->save()) {
                               $productjson = Json::encode($model);
                               $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-nam.json');
                               $fp = fopen($jsonfile, 'w+');
                               fwrite($fp, $productjson);
                               fclose($fp);
                           }

                        } else {
                            // new Result
                            $model = new Result();
                            $model->postdate = $listDate;
                            $model->result = $stripTagStr;
                            $model->province = $maTinh[0];
                            if ($model->save()) {
                                $productjson = Json::encode($model);
                                $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-nam.json');
                                $fp = fopen($jsonfile, 'w+');
                                fwrite($fp, $productjson);
                                fclose($fp);
                            }
                        }
                    }
                }else{
                    $searchModel = new ResultQuery();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    $dataProvider->query->andWhere(['postdate'=>'0']);
                }

            }
            $searchModel = new ResultQuery();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->andWhere(['>=', 'postdate', $from_date])->andWhere(['<=', 'postdate', $to_date])->all();
        }else{
            // dữ liệu ket-qua-xo-so lấy ra danh sách tất cả k chia theo địa chỉ lúc query
            // check thời gian để xử lý việc lưu dữ liệu
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $time = date('H:i:s');
            // check lịch
            $weekday = date("l");
            $weekday = strtolower($weekday);
            switch ($weekday) {
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
            // end check lich
            if ((strtotime($time) > strtotime("18:00:00") && strtotime($time) < strtotime("19:00:00"))) {
                // neu tu 6->7h là xổ số miền bắc
                $url = 'http://ketqua.vn/truc-tiep-xo-so-mien-bac';
                $document = new Document();
                $document->loadHtmlFile($url);
                $input = [];
                $input[0] = $document->find('td[class=mb_g0 red]')[0];
                $input[1] = $document->find('td[class=mb_g1]')[0];
                $input[2] = $document->find('td[class=mb_g21]')[0];
                $input[3] = $document->find('td[class=mb_g22]')[0];
                $input[4] = $document->find('td[class=mb_g31]')[0];
                $input[5] = $document->find('td[class=mb_g32]')[0];
                $input[6] = $document->find('td[class=mb_g33]')[0];
                $input[7] = $document->find('td[class=mb_g34]')[0];
                $input[8] = $document->find('td[class=mb_g35]')[0];
                $input[9] = $document->find('td[class=mb_g36]')[0];
                $input[10] = $document->find('td[class=mb_g41]')[0];
                $input[11] = $document->find('td[class=mb_g42]')[0];
                $input[12] = $document->find('td[class=mb_g43]')[0];
                $input[13] = $document->find('td[class=mb_g44]')[0];
                $input[14] = $document->find('td[class=mb_g51]')[0];
                $input[15] = $document->find('td[class=mb_g52]')[0];
                $input[16] = $document->find('td[class=mb_g53]')[0];
                $input[17] = $document->find('td[class=mb_g54]')[0];
                $input[18] = $document->find('td[class=mb_g55]')[0];
                $input[19] = $document->find('td[class=mb_g56]')[0];
                $input[20] = $document->find('td[class=mb_g61]')[0];
                $input[21] = $document->find('td[class=mb_g62]')[0];
                $input[22] = $document->find('td[class=mb_g63]')[0];
                $input[23] = $document->find('td[class=mb_g71]')[0];
                $input[24] = $document->find('td[class=mb_g72]')[0];
                $input[25] = $document->find('td[class=mb_g73]')[0];
                $input[26] = $document->find('td[class=mb_g74]')[0];
                $input[27] = $document->find('h4[class=mb_date_info]')[0];

                // quy chuẩn lưu data text
                $str = "";
                $str .= $input[0] . '|,';
                $str .= $input[1] . '|,';
                $str .= $input[2] . ',' . $input[3] . '|,';
                $str .= $input[4] . ',' . $input[5] . ',' . $input[6] . ',' . $input[7] . ',' . $input[8] . ',' . $input[9] . '|,';
                $str .= $input[10] . ',' . $input[11] . ',' . $input[12] . ',' . $input[13] . '|,';
                $str .= $input[14] . ',' . $input[15] . ',' . $input[16] . ',' . $input[17] . ',' . $input[18] . ',' . $input[19] . '|,';
                $str .= $input[20] . ',' . $input[21] . ',' . $input[22] . '|,';
                $str .= $input[23] . ',' . $input[24] . ',' . $input[25] . ',' . $input[26];
                $stripTagStr = strip_tags(trim(preg_replace('/[\t\s]/', '', $str)));

                // new Result
                // check ngày tồn tại
                $date = Result::find()
                    ->andWhere(['postdate' => date('Y-m-d')])
                    ->andWhere(['province' => 129])->count();
                if ($date > 0) {
                   $model = Result::find()
                       ->andWhere(['postdate' => date('Y-m-d')])
                       ->andWhere(['province' => 129])->one();
                   $model->postdate = date('Y-m-d');
                   $model->result = $stripTagStr;
                   $model->province = 129;
                   if ($model->save()) {
                       $productjson = Json::encode($model);
                       $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-bac.json');
                       $fp = fopen($jsonfile, 'w+');
                       fwrite($fp, $productjson);
                       fclose($fp);
                   }

                } else {
                    $model = new Result();
                    $model->postdate = date('Y-m-d');
                    $model->result = $stripTagStr;
                    $model->province = 129;
                    if ($model->save()) {
                        $productjson = Json::encode($model);
                        $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-bac.json');
                        $fp = fopen($jsonfile, 'w+');
                        fwrite($fp, $productjson);
                        fclose($fp);
                    }
                }
            }

            elseif ((strtotime($time) > strtotime("17:00:00") && strtotime($time) < strtotime("18:00:00"))) {
                //miền trung
                //  danh sách các tỉnh theo thời gian ngày
                $i = 0;
                foreach ($mt as $maTinh) {
                    $url = "http://ketqua.vn/kqxs$maTinh[1]-ket-qua-xo-so-$maTinh[2]";
                    $document = new Document();
                    $document->loadHtmlFile($url);
                    $codeMaTinh = $maTinh[4];
                    $input = [];
                    $input[$i][0] = $document->find("td[class='" . $codeMaTinh . "_g0 red']")[0];
                    $input[$i][1] = $document->find("td[class='" . $codeMaTinh . "_g1']")[0];
                    $input[$i][2] = $document->find("td[class='" . $codeMaTinh . "_g2']")[0];
                    $input[$i][3] = $document->find("td[class='" . $codeMaTinh . "_g31']")[0];
                    $input[$i][4] = $document->find("td[class='" . $codeMaTinh . "_g32']")[0];
                    $input[$i][5] = $document->find("td[class='" . $codeMaTinh . "_g41']")[0];
                    $input[$i][6] = $document->find("td[class='" . $codeMaTinh . "_g42']")[0];
                    $input[$i][7] = $document->find("td[class='" . $codeMaTinh . "_g43']")[0];
                    $input[$i][8] = $document->find("td[class='" . $codeMaTinh . "_g44']")[0];
                    $input[$i][9] = $document->find("td[class='" . $codeMaTinh . "_g45']")[0];
                    $input[$i][10] = $document->find("td[class='" . $codeMaTinh . "_g46']")[0];
                    $input[$i][11] = $document->find("td[class='" . $codeMaTinh . "_g47']")[0];
                    $input[$i][12] = $document->find("td[class='" . $codeMaTinh . "_g5']")[0];
                    $input[$i][13] = $document->find("td[class='" . $codeMaTinh . "_g61']")[0];
                    $input[$i][14] = $document->find("td[class='" . $codeMaTinh . "_g62']")[0];
                    $input[$i][15] = $document->find("td[class='" . $codeMaTinh . "_g63']")[0];
                    $input[$i][16] = $document->find("td[class='" . $codeMaTinh . "_g7']")[0];
                    $input[$i][17] = $document->find("td[class='" . $codeMaTinh . "_g8']")[0];
                    $input[$i][18] = $document->find("h4")[0];

                    // quy chuẩn lưu data text
                    $str = "";
                    $str .= $input[$i][0] . "|,";
                    $str .= $input[$i][1] . "|,";
                    $str .= $input[$i][2] . "|,";
                    $str .= $input[$i][3] . ',' . $input[$i][4] . "|,";
                    $str .= $input[$i][9] . ',' . $input[$i][10] . ',' . $input[$i][11] . ',' . $input[$i][5] . ',' . $input[$i][6] . ',' . $input[$i][7] . ',' . $input[$i][8] . "|,";
                    $str .= $input[$i][12] . "|,";
                    $str .= $input[$i][13] . ',' . $input[$i][14] . ',' . $input[$i][15] . "|,";
                    $str .= $input[$i][16] . "|,";
                    $str .= $input[$i][17];
                    $stripTagStr = strip_tags(trim(preg_replace('/[\t\s]/', '', $str)));

                    $i++;
                    // new Result
                    // check ngày tồn tại
                    $date = Result::find()
                        ->andWhere(['postdate' => date('Y-m-d')])
                        ->andWhere(['province' => $maTinh[0]])->count();
                    if ($date > 0) {
                       $model = Result::find()
                           ->andWhere(['postdate' => date('Y-m-d')])
                           ->andWhere(['province' => $maTinh[0]])->one();
                       $model->postdate = date('Y-m-d');
                       $model->result = $stripTagStr;
                       $model->province = $maTinh[0];
                       if ($model->save()) {
                           $productjson = Json::encode($model);
                           $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-trung.json');
                           $fp = fopen($jsonfile, 'w+');
                           fwrite($fp, $productjson);
                           fclose($fp);
                       }
                    } else {
                        $model = new Result();
                        $model->postdate = date('Y-m-d');
                        $model->result = $stripTagStr;
                        $model->province = $maTinh[0];
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-trung.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }
                }

            } elseif (strtotime($time) > strtotime("16:00:00")) {
                // miền nam
                $i = 0;
                foreach ($mn as $maTinh) {
                    $url = "http://ketqua.vn/kqxs$maTinh[1]-ket-qua-xo-so-$maTinh[2]";

                    $document = new Document();
                    $document->loadHtmlFile($url);
                    $codeMaTinh = $maTinh[4];
                    $input = [];
                    $input[$i][0] = $document->find("td[class='" . $codeMaTinh . "_g0 red']")[0];
                    $input[$i][1] = $document->find("td[class='" . $codeMaTinh . "_g1']")[0];
                    $input[$i][2] = $document->find("td[class='" . $codeMaTinh . "_g2']")[0];
                    $input[$i][3] = $document->find("td[class='" . $codeMaTinh . "_g31']")[0];
                    $input[$i][4] = $document->find("td[class='" . $codeMaTinh . "_g32']")[0];
                    $input[$i][5] = $document->find("td[class='" . $codeMaTinh . "_g41']")[0];
                    $input[$i][6] = $document->find("td[class='" . $codeMaTinh . "_g42']")[0];
                    $input[$i][7] = $document->find("td[class='" . $codeMaTinh . "_g43']")[0];
                    $input[$i][8] = $document->find("td[class='" . $codeMaTinh . "_g44']")[0];
                    $input[$i][9] = $document->find("td[class='" . $codeMaTinh . "_g45']")[0];
                    $input[$i][10] = $document->find("td[class='" . $codeMaTinh . "_g46']")[0];
                    $input[$i][11] = $document->find("td[class='" . $codeMaTinh . "_g47']")[0];
                    $input[$i][12] = $document->find("td[class='" . $codeMaTinh . "_g5']")[0];
                    $input[$i][13] = $document->find("td[class='" . $codeMaTinh . "_g61']")[0];
                    $input[$i][14] = $document->find("td[class='" . $codeMaTinh . "_g62']")[0];
                    $input[$i][15] = $document->find("td[class='" . $codeMaTinh . "_g63']")[0];
                    $input[$i][16] = $document->find("td[class='" . $codeMaTinh . "_g7']")[0];
                    $input[$i][17] = $document->find("td[class='" . $codeMaTinh . "_g8']")[0];
                    $input[$i][18] = $document->find("h4")[0];

                    // quy chuẩn lưu data text
                    $str = "";
                    $str .= $input[$i][0] . "|,";
                    $str .= $input[$i][1] . "|,";
                    $str .= $input[$i][2] . "|,";
                    $str .= $input[$i][3] . ',' . $input[$i][4] . "|,";
                    $str .= $input[$i][9] . ',' . $input[$i][10] . ',' . $input[$i][11] . ',' . $input[$i][5] . ',' . $input[$i][6] . ',' . $input[$i][7] . ',' . $input[$i][8] . "|,";
                    $str .= $input[$i][12] . "|,";
                    $str .= $input[$i][13] . ',' . $input[$i][14] . ',' . $input[$i][15] . "|,";
                    $str .= $input[$i][16] . "|,";
                    $str .= $input[$i][17];
                    $stripTagStr = strip_tags(trim(preg_replace('/[\t\s]/', '', $str)));
                    $i++;
                    // check ngày tồn tại
                    $date = Result::find()
                        ->andWhere(['postdate' => date('Y-m-d')])
                        ->andWhere(['province' => $maTinh[0]])->count();
                    if ($date > 0) {
                        // update result
                       $model = Result::find()
                           ->andWhere(['postdate' => date('Y-m-d')])
                           ->andWhere(['province' => $maTinh[0]])->one();
                       $model->postdate = date('Y-m-d');
                       $model->result = $stripTagStr;
                       $model->province = $maTinh[0];
                       if ($model->save()) {
                           $productjson = Json::encode($model);
                           $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-nam.json');
                           $fp = fopen($jsonfile, 'w+');
                           fwrite($fp, $productjson);
                           fclose($fp);
                       }
                    } else {
                        // new Result
                        $model = new Result();
                        $model->postdate = date('Y-m-d');
                        $model->result = $stripTagStr;
                        $model->province = $maTinh[0];
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/xo-so-mien-nam.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }
                }
            }

            $searchModel = new ResultQuery();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->orderBy(['id' => SORT_DESC]);
        }
        return $this->render('ket-qua-xo-so', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionVietlott655($inputKi=null)
    {
        $inputKi = Yii::$app->request->get('inputKi');

        if ($inputKi != '' && $inputKi != null){
           
            $lengthInput= strlen($inputKi);
            
            if( (int)$lengthInput == 5){ 
                $urlHienTai = 'http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/655';
                $documentHienTai = new Document();
                $documentHienTai->loadHtmlFile($urlHienTai);
                $kyHienTai = $documentHienTai->find('h5 > b')[0];
                $kyValueHienTai = preg_replace('/#/', '', $kyHienTai);
                $url = "http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/655?id=".$inputKi."&nocatche=1";
                if( (int)strip_tags($kyValueHienTai) < (int)$inputKi ){
                    $searchModel = new Power655Query();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    $dataProvider->query->where(['ki' => $inputKi]);
                    Yii::$app->session->setFlash('success', Yii::t('app','Kì tìm kiếm không hợp lệ vui lòng kiểm tra lại'));
                }else{
                    $document = new Document();
                    $document->loadHtmlFile($url);
                    $num1 = $document->find('span[class=bong_tron small]')[0];
                    $num2 = $document->find('span[class=bong_tron small]')[1];
                    $num3 = $document->find('span[class=bong_tron small]')[2];
                    $num4 = $document->find('span[class=bong_tron small]')[3];
                    $num5 = $document->find('span[class=bong_tron small]')[4];
                    $num6 = $document->find('span[class=bong_tron small]')[5];
                    $num7 = $document->find('span[class=bong_tron small no-margin-right active]')[0];
                    $jackpot1 = $document->find('td[class=color_red text-right]')[0];
                    $jackpot2 = $document->find('td[class=color_red text-right]')[1];
                    $j1 = $document->find('td[class=text-right]')[0];
                    $j2 = $document->find('td[class=text-right]')[1];
                    $giaiNhat = $document->find('td[class=text-right]')[2];
                    $giaiNhi = $document->find('td[class=text-right]')[3];
                    $giaiBa = $document->find('td[class=text-right]')[4];
                    $ky = $document->find('h5 > b')[0];
                    $kyValue = preg_replace('/#/', '', $ky);
                    $ngay = $document->find('h5 > b')[1];

                    $check = Power655::find()->andWhere(['ki' => $inputKi])->count();
                    if ($check > 0) {
                        $model = Power655::find()->where(['ki' => $inputKi])->one();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->so7 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num7)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport1 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->jackport2 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot2)));;
                        $model->j1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->j2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j2)));;
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/power655.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    } else {
                        $model = new Power655();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->so7 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num7)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport1 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->jackport2 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot2)));;
                        $model->j1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->j2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j2)));;
                        $model->ki = $inputKi;
                        $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/power655.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }
                    $searchModel = new Power655Query();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    $dataProvider->query->where(['ki' => $inputKi]);
                }
            }
            else{
                $searchModel = new Power655Query();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->query->where(['ki' => $inputKi]);
            }
        }else{
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $date = date('Y-m-d');
            $day = date('w', strtotime($date));
            $time = date('H:i:s');
            if ($day == 2 || $day == 4 || $day == 6) {
                if ((strtotime($time) > strtotime("18:00:00") && strtotime($time) < strtotime("18:30:00"))) {
                    $url = 'http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/655';
                    $document = new Document();
                    $document->loadHtmlFile($url);

                    $num1 = $document->find('span[class=bong_tron small]')[0];
                    $num2 = $document->find('span[class=bong_tron small]')[1];
                    $num3 = $document->find('span[class=bong_tron small]')[2];
                    $num4 = $document->find('span[class=bong_tron small]')[3];
                    $num5 = $document->find('span[class=bong_tron small]')[4];
                    $num6 = $document->find('span[class=bong_tron small]')[5];
                    $num7 = $document->find('span[class=bong_tron small no-margin-right active]')[0];
                    $jackpot1 = $document->find('td[class=color_red text-right]')[0];
                    $jackpot2 = $document->find('td[class=color_red text-right]')[1];
                    $j1 = $document->find('td[class=text-right]')[0];
                    $j2 = $document->find('td[class=text-right]')[1];
                    $giaiNhat = $document->find('td[class=text-right]')[2];
                    $giaiNhi = $document->find('td[class=text-right]')[3];
                    $giaiBa = $document->find('td[class=text-right]')[4];
                    $ky = $document->find('h5 > b')[0];
                    $kyValue = preg_replace('/#/', '', $ky);
                    $ngay = $document->find('h5 > b')[1];
                    $date = date('Y-m-d');
                    $check = Power655::find()->andWhere(['ngay' => $date])->count();
                    if ($check > 0) {
                        $model = Power655::find()->where(['ngay' => $date])->one();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->so7 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num7)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport1 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->jackport2 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot2)));;
                        $model->j1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->j2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j2)));;
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ngay = date('Y-m-d');
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/power655.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    } else {
                        $model = new Power655();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->so7 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num7)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport1 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->jackport2 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot2)));;
                        $model->j1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->j2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j2)));;
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ngay = date('Y/m/d');
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/power655.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }
                }
            }
            $searchModel = new Power655Query();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->orderBy(['id' => SORT_DESC]);
        }

        return $this->render('vietlott655', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMega645()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date('Y-m-d');
        $day = date('w', strtotime($date));
        $time = date('H:i:s');
        $inputKi = Yii::$app->request->get('inputKi');
        if ($inputKi != '' && $inputKi != null){
            $lengthInput= strlen($inputKi);
            if( (int)$lengthInput == 5){
                $urlHienTai = 'http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/645';
                $documentHienTai = new Document();
                $documentHienTai->loadHtmlFile($urlHienTai);
                $kyHienTai = $documentHienTai->find('h5 > b')[0];
                $kyValueHienTai = preg_replace('/#/', '', $kyHienTai);
                $url = "http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/645?id=".$inputKi."&nocatche=1";

                if( (int)strip_tags($kyValueHienTai) < (int)$inputKi ){
                    $searchModel = new Mega645Query();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    $dataProvider->query->where(['ki' => $inputKi]);
                    Yii::$app->session->setFlash('success', Yii::t('app','Kì tìm kiếm không hợp lệ vui lòng kiểm tra lại'));

                }else{
                    $document = new Document();
                    $document->loadHtmlFile($url);
                    $num1 = $document->find('span[class=bong_tron]')[0];
                    $num2 = $document->find('span[class=bong_tron]')[1];
                    $num3 = $document->find('span[class=bong_tron]')[2];
                    $num4 = $document->find('span[class=bong_tron]')[3];
                    $num5 = $document->find('span[class=bong_tron]')[4];
                    $num6 = $document->find('span[class=bong_tron no-margin-right]')[0];
                    $jackpot1 = $document->find('td[class=color_red text-right]')[0];
                    $j1 = $document->find('td[class=text-right]')[0];
                    $giaiNhat = $document->find('td[class=text-right]')[1];
                    $giaiNhi = $document->find('td[class=text-right]')[2];
                    $giaiBa = $document->find('td[class=text-right]')[3];
                    $ngay = $document->find('h5 > b')[1];
                    $ky = $document->find('h5 > b')[0];
                    $ngay = $document->find('h5 > b')[1];
                    $kyValue = preg_replace('/#/', '', $ky);

                    $check = Mega645::find()->andWhere(['ki' => $inputKi])->count();
                    if ($check > 0) {
                        $model = Mega645::find()->where(['ki' => $inputKi])->one();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->g0 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ki = $inputKi;
                        $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));

                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/mega645.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    } else {
                        $model = new Mega645();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->g0 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ki = $inputKi;
                        $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));

                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/mega645.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }
                    $searchModel = new Mega645Query();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    $dataProvider->query->where(['ki' => $inputKi]);
                }
            }else{
                $searchModel = new Mega645Query();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->query->where(['ki' => $inputKi]);
            }
        }else{
            if ($day == 3 || $day == 5 || $day == 7) {
                if ((strtotime($time) > strtotime("18:00:00") && strtotime($time) < strtotime("18:30:00"))) {
                    $url = 'http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/645';
                    $document = new Document();
                    $document->loadHtmlFile($url);
                    $num1 = $document->find('span[class=bong_tron]')[0];
                    $num2 = $document->find('span[class=bong_tron]')[1];
                    $num3 = $document->find('span[class=bong_tron]')[2];
                    $num4 = $document->find('span[class=bong_tron]')[3];
                    $num5 = $document->find('span[class=bong_tron]')[4];
                    $num6 = $document->find('span[class=bong_tron no-margin-right]')[0];
                    $jackpot1 = $document->find('td[class=color_red text-right]')[0];
                    $j1 = $document->find('td[class=text-right]')[0];
                    $giaiNhat = $document->find('td[class=text-right]')[1];
                    $giaiNhi = $document->find('td[class=text-right]')[2];
                    $giaiBa = $document->find('td[class=text-right]')[3];
                    $ky = $document->find('h5 > b')[0];
                    $kyValue = preg_replace('/#/', '', $ky);
                    $date = date('Y-m-d');
                    $check = Mega645::find()->andWhere(['ngay' => $date])->count();
                    if ($check > 0) {
                        $model = Mega645::find()->where(['ngay' => $date])->one();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->g0 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ngay = date('Y-m-d');

                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/mega645.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    } else {
                        $model = new Mega645();
                        $model->so1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num1)));;
                        $model->so2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num2)));;
                        $model->so3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num3)));;
                        $model->so4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num4)));;
                        $model->so5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num5)));;
                        $model->so6 = strip_tags(trim(preg_replace('/[\t\s]/', '', $num6)));;
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhat)));;
                        $model->g2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $giaiNhi)));;
                        $model->g3 = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $giaiBa)));;
                        $model->jackport = strip_tags(trim(preg_replace('/[\t\s\.\,]/', '', $jackpot1)));;
                        $model->g0 = strip_tags(trim(preg_replace('/[\t\s]/', '', $j1)));;
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ngay = date('Y/m/d');

                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/mega645.json');

                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }
                }
            }
            $searchModel = new Mega645Query();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->orderBy(['id' => SORT_DESC]);
        }
        return $this->render('mega645', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionMax4d()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date('Y-m-d');
        $day = date('w', strtotime($date));
        $time = date('H:i:s');
        $inputKi = Yii::$app->request->get('inputKi');
        if ($inputKi != '' && $inputKi != null){
            $lengthInput= strlen($inputKi);
            if( (int)$lengthInput == 5){
                $urlHienTai = 'http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-4d';
                $documentHienTai = new Document();
                $documentHienTai->loadHtmlFile($urlHienTai);
                $kyHienTai = $documentHienTai->find('h5 > b')[0];
                $kyValueHienTai = preg_replace('/#/', '', $kyHienTai);
                if( (int)strip_tags($kyValueHienTai) < (int)$inputKi ){
                    $searchModel = new Max4dQuery();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    $dataProvider->query->where(['ki' => $inputKi]);
                }else{
                    $urlKi = "https://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-4d?id=".$inputKi."&nocatche=1";
                    $document = new Document();
                    $document->loadHtmlFile($urlKi);
                    $ngay = $document->find('h5 > b')[1];
                    //259 là kì thay đổi giao diên mới
                    if ($inputKi < 259){
                        // nếu kì dưới 259 thì giao diện cũ k có
                        // th41 th42 th43  th61 th62 th63 th121 th122 th123 th241 th242 th243 = 0
                        $g1 = $document->find('td[class=color_red nowrap]')[0];
                        $g2 = explode(' ', $document->find('td[class=color_red nowrap]')[1]);
                        $g21 = $g2[2];
                        $g22 = $g2[3];
                        $g3 =  explode(' ', $document->find('td[class=color_red nowrap]')[2]);
                        $g31 = $g3[2];
                        $g32 = $g3[3];
                        $g33 = $g3[4];
                        $g4 = $document->find('td[class=color_red nowrap]')[3];
                        $g5 = $document->find('td[class=color_red nowrap]')[4];
                        $t2 = $document->find('td[class=text-right]')[0];
                        $t3 = $document->find('td[class=text-right]')[1];
                        $t4 = $document->find('td[class=text-right]')[2];
                        $t5 = $document->find('td[class=text-right]')[3];
                        $t6 = $document->find('td[class=text-right]')[4];
                        $check = Max4d::find()->andWhere(['ki' => $inputKi])->count();
                        if ($check > 0) {
                            $model = Max4d::find()->where(['ki' => $inputKi])->one();
                            $model->ki = $inputKi;
                            $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));
                            $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g1)));
                            $model->g21 = strip_tags(trim(preg_replace('/nowrap">/', '', $g21)));
                            $model->g22 = strip_tags(trim(preg_replace('/nowrap">/', '', $g21)));
                            $model->g31 = strip_tags(trim(preg_replace('/nowrap">/', '', $g31)));
                            $model->g32 = strip_tags(trim(preg_replace('/nowrap">/', '', $g32)));
                            $model->g33 = strip_tags(trim(preg_replace('/nowrap">/', '', $g33)));
                            $model->g4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g4)));
                            $model->g5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g5)));
                            $model->t1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t2)));
                            $model->t2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t3)));
                            $model->t3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t4)));
                            $model->t4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t5)));
                            $model->t5 = (int)strip_tags(trim(preg_replace('/[,]/', '', $t6)));
                            $model->th41 = 0;
                            $model->th42 = 0;
                            $model->th43 = 0;
                            $model->th61 = 0;
                            $model->th62 = 0;
                            $model->th63 = 0;
                            $model->th121 =0;
                            $model->th122 = 0;
                            $model->th123 = 0;
                            $model->th241 = 0;
                            $model->th242 = 0;
                            $model->th243 = 0;

                            if ($model->save()) {
                                $productjson = Json::encode($model);
                                $jsonfile = Yii::getAlias('@webroot/json/max4d.json');
                                $fp = fopen($jsonfile, 'w+');
                                fwrite($fp, $productjson);
                                fclose($fp);
                            }
                        } else {
                            $model = new Max4d();
                            $model->ki = $inputKi;
                            $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));
                            $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g1)));
                            $model->g21 = strip_tags(trim(preg_replace('/nowrap">/', '', $g21)));
                            $model->g22 = strip_tags(trim(preg_replace('/nowrap">/', '', $g22)));
                            $model->g31 = strip_tags(trim(preg_replace('/nowrap">/', '', $g31)));
                            $model->g32 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g32)));
                            $model->g33 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g33)));
                            $model->g4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g4)));
                            $model->g5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g5)));
                            $model->t1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t2)));
                            $model->t2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t3)));
                            $model->t3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t4)));
                            $model->t4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t5)));
                            $model->t5 = (int)strip_tags(trim(preg_replace('/[,]/', '', $t6)));
                            $model->th41 = 0;
                            $model->th42 = 0;
                            $model->th43 = 0;
                            $model->th61 = 0;
                            $model->th62 = 0;
                            $model->th63 = 0;
                            $model->th121 =0;
                            $model->th122 = 0;
                            $model->th123 = 0;
                            $model->th241 = 0;
                            $model->th242 = 0;
                            $model->th243 = 0;
                            if ($model->save()) {
                                $productjson = Json::encode($model);
                                $jsonfile = Yii::getAlias('@webroot/json/max4d.json');
                                $fp = fopen($jsonfile, 'w+');
                                fwrite($fp, $productjson);
                                fclose($fp);
                            }
                        }
                        $searchModel = new Max4dQuery();
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        $dataProvider->query->where(['ki' => $inputKi]);
                    }else{
                        // nếu kì trên 259 giao diện mới
                        $g1 = $document->find('td[class=color_red]')[0];
                        $g2 = preg_replace('/class="color_red">/', '', explode(' ', $document->find('td[class=color_red]')[1]));
                        $g21 = $g2[1];
                        $g22 = $g2[2];
                        $g3 = preg_replace('/class="color_red">/', '', explode(' ', $document->find('td[class=color_red]')[2]));
                        $g31 = $g3[1];
                        $g32 = $g3[2];
                        $g33 = $g3[3];
                        $g4 = $document->find('td[class=color_red]')[3];
                        $g5 = $document->find('td[class=color_red]')[4];
                        $t2 = $document->find('td[class=text-right]')[4];
                        $t22 = $document->find('td[class=text-right]')[5];
                        $t23 = $document->find('td[class=text-right]')[6];
                        $t24 = $document->find('td[class=text-right]')[7];
                        $t25 = $document->find('td[class=text-right]')[8];
                        $t3 = $document->find('td[class=text-right]')[9];
                        $t32 = $document->find('td[class=text-right]')[10];
                        $t33 =$document->find('td[class=text-right]')[11];
                        $t34 = $document->find('td[class=text-right]')[12];
                        $t35 = $document->find('td[class=text-right]')[13];
                        $t4 = $document->find('td[class=text-right]')[14];
                        $t42 = $document->find('td[class=text-right]')[15];
                        $t43 = $document->find('td[class=text-right]')[16];
                        $t44 = $document->find('td[class=text-right]')[17];
                        $t45 = $document->find('td[class=text-right]')[18];
                        $t5 = $document->find('td[class=text-right]')[19];
                        $t6 = $document->find('td[class=text-right]')[24];
                        $ky = $document->find('h5 > b')[0];
                        $ngay = $document->find('h5 > b')[1];
                        $kyValue = preg_replace('/#/', '', $ky);
                        $check = Max4d::find()->andWhere(['ki' => $inputKi])->count();
                        if ($check > 0) {
                            $model = Max4d::find()->where(['ki' => $inputKi])->one();
                            $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                            $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));
                            $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g1)));;
                            $model->g21 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g21)));;
                            $model->g22 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g22)));;
                            $model->g31 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g31)));;
                            $model->g32 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g32)));;
                            $model->g33 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g33)));;
                            $model->g4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g4)));;
                            $model->g5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g5)));;
                            $model->t1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t2)));;
                            $model->t2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t3)));;
                            $model->t3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t4)));;
                            $model->t4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t5)));;
                            $model->t5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t6)));;
                            $model->th41 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t22)));;
                            $model->th42 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t32)));;
                            $model->th43 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t42)));;
                            $model->th61 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t23)));;
                            $model->th62 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t33)));;
                            $model->th63 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t43)));;
                            $model->th121 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t24)));;
                            $model->th122 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t34)));;
                            $model->th123 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t44)));;
                            $model->th241 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t25)));;
                            $model->th242 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t35)));;
                            $model->th243 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t45)));;
                            if ($model->save()) {
                                $productjson = Json::encode($model);
                                $jsonfile = Yii::getAlias('@webroot/json/max4d.json');
                                $fp = fopen($jsonfile, 'w+');
                                fwrite($fp, $productjson);
                                fclose($fp);
                            }
                        } else {
                            $model = new Max4d();
                            $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                            $model->ngay = date('Y-m-d',strtotime(strip_tags(trim(preg_replace('/b/', '', str_replace('/', '-', $ngay))))));
                            $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g1)));;
                            $model->g21 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g21)));;
                            $model->g22 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g22)));;
                            $model->g31 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g31)));;
                            $model->g32 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g32)));;
                            $model->g33 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g33)));;
                            $model->g4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g4)));;
                            $model->g5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g5)));;
                            $model->t1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t2)));;
                            $model->t2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t3)));;
                            $model->t3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t4)));;
                            $model->t4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t5)));;
                            $model->t5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t6)));;
                            $model->th41 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t22)));;
                            $model->th42 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t32)));;
                            $model->th43 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t42)));;
                            $model->th61 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t23)));;
                            $model->th62 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t33)));;
                            $model->th63 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t43)));;
                            $model->th121 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t24)));;
                            $model->th122 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t34)));;
                            $model->th123 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t44)));;
                            $model->th241 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t25)));;
                            $model->th242 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t35)));;
                            $model->th243 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t45)));;
                            if ($model->save()) {
                                $productjson = Json::encode($model);
                                $jsonfile = Yii::getAlias('@webroot/json/max4d.json');
                                $fp = fopen($jsonfile, 'w+');
                                fwrite($fp, $productjson);
                                fclose($fp);
                            }
                        }
                    }
                }
                $searchModel = new Max4dQuery();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->query->where(['ki' => $inputKi]);
            }else{
                $searchModel = new Max4dQuery();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->query->where(['ki' => $inputKi]);
            }
        }else{
            if($day==2 || $day == 4 || $day == 6){
                if ((strtotime($time) > strtotime("15:00:00") && strtotime($time) < strtotime("18:30:00"))) {
                    $url = 'http://vietlott.vn/vi/trung-thuong/ket-qua-trung-thuong/max-4d';
                    $document = new Document();
                    $document->loadHtmlFile($url);
                    $g1 = $document->find('td[class=color_red]')[0];
                    $g2 = preg_replace('/class="color_red">/', '', explode(' ', $document->find('td[class=color_red]')[1]));
                    $g21 = $g2[1];
                    $g22 = $g2[2];
                    $g3 = preg_replace('/class="color_red">/', '', explode(' ', $document->find('td[class=color_red]')[2]));
                    $g31 = $g3[1];
                    $g32 = $g3[2];
                    $g33 = $g3[3];
                    $g4 = $document->find('td[class=color_red]')[3];
                    $g5 = $document->find('td[class=color_red]')[4];
                    $t2 = $document->find('td[class=text-right]')[4];
                    $t22 = $document->find('td[class=text-right]')[5];
                    $t23 = $document->find('td[class=text-right]')[6];
                    $t24 = $document->find('td[class=text-right]')[7];
                    $t25 = $document->find('td[class=text-right]')[8];
                    $t3 = $document->find('td[class=text-right]')[9];
                    $t32 = $document->find('td[class=text-right]')[10];
                    $t33 =$document->find('td[class=text-right]')[11];
                    $t34 = $document->find('td[class=text-right]')[12];
                    $t35 = $document->find('td[class=text-right]')[13];
                    $t4 = $document->find('td[class=text-right]')[14];
                    $t42 = $document->find('td[class=text-right]')[15];
                    $t43 = $document->find('td[class=text-right]')[16];
                    $t44 = $document->find('td[class=text-right]')[17];
                    $t45 = $document->find('td[class=text-right]')[18];
                    $t5 = $document->find('td[class=text-right]')[19];
                    $t6 = $document->find('td[class=text-right]')[24];
                    $ky = $document->find('h5 > b')[0];
                    $kyValue = preg_replace('/#/', '', $ky);
                    $date = date('Y-m-d');
                    $check = Max4d::find()->andWhere(['ngay' => $date])->count();
                    if ($check > 0) {
                        $model = Max4d::find()->where(['ngay' => $date])->one();
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ngay = date('Y-m-d');
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g1)));;
                        $model->g21 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g21)));;
                        $model->g22 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g22)));;
                        $model->g31 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g31)));;
                        $model->g32 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g32)));;
                        $model->g33 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g33)));;
                        $model->g4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g4)));;
                        $model->g5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g5)));;
                        $model->t1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t2)));;
                        $model->t2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t3)));;
                        $model->t3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t4)));;
                        $model->t4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t5)));;
                        $model->t5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t6)));;
                        $model->th41 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t22)));;
                        $model->th42 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t32)));;
                        $model->th43 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t42)));;
                        $model->th61 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t23)));;
                        $model->th62 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t33)));;
                        $model->th63 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t43)));;
                        $model->th121 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t24)));;
                        $model->th122 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t34)));;
                        $model->th123 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t44)));;
                        $model->th241 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t25)));;
                        $model->th242 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t35)));;
                        $model->th243 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t45)));;
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/max4d.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    } else {
                        $model = new Max4d();
                        $model->ki = strip_tags(trim(preg_replace('/[\t\s]/', '', $kyValue)));;
                        $model->ngay = date('Y-m-d');
                        $model->g1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g1)));;
                        $model->g21 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g21)));;
                        $model->g22 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g22)));;
                        $model->g31 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g31)));;
                        $model->g32 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g32)));;
                        $model->g33 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g33)));;
                        $model->g4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g4)));;
                        $model->g5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $g5)));;
                        $model->t1 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t2)));;
                        $model->t2 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t3)));;
                        $model->t3 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t4)));;
                        $model->t4 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t5)));;
                        $model->t5 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t6)));;
                        $model->th41 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t22)));;
                        $model->th42 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t32)));;
                        $model->th43 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t42)));;
                        $model->th61 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t23)));;
                        $model->th62 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t33)));;
                        $model->th63 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t43)));;
                        $model->th121 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t24)));;
                        $model->th122 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t34)));;
                        $model->th123 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t44)));;
                        $model->th241 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t25)));;
                        $model->th242 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t35)));;
                        $model->th243 = strip_tags(trim(preg_replace('/[\t\s]/', '', $t45)));;
                        if ($model->save()) {
                            $productjson = Json::encode($model);
                            $jsonfile = Yii::getAlias('@webroot/json/max4d.json');
                            $fp = fopen($jsonfile, 'w+');
                            fwrite($fp, $productjson);
                            fclose($fp);
                        }
                    }
                }
            }
            $searchModel = new Max4dQuery();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            $dataProvider->query->orderBy(['id' => SORT_DESC]);
        }

        return $this->render('max4d', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLichQuay($weekdayName){
        switch($weekdayName) {
            case 'monday':
                $weekday = 'T.2';

                $mt=array(array(183,"py","phu-yen","Phú Yên","py",27),array(188,"tth","thua-thien-hue","Thừa Thiên Huế","tth",26));
                $mn=array(array(166,"hcm","ho-chi-minh","Hồ Chí Minh","hcm",1),array(160,"cm","ca-mau","Cà Mau","cm",3),array(164,"dt","dong-thap","Đồng Tháp","dt",2));

                $mientrung = "PY,TTH";
                $miennam = "HCM,CM,DT";

                $mientrungName = "Phú Yên,Thừa Thiên Huế";
                $miennamName = "Hồ Chí Minh,Cà Mau,Đồng Tháp";
                $mienbacName = "Hà Nội";
                break;
            case 'tuesday':
                $weekday = 'T.3';

                $mt=array(array(177,"dl","dac-lac","Đắc Lắc","dlk",29),array(186,"qn","quang-nam","Quảng Nam","qnm",28));
                $mn=array(array(155,"bt","ben-tre","Bến Tre","btr",7),array(174,"vt","vung-tau","Vũng Tàu","vt",8),array(120,"bl","bac-lieu","Bạc Liêu","bl",9));

                $mientrungName = "Đắc Lắc, Quảng Nam";
                $miennamName = "Bến Tre,Vũng Tàu,Bạc Liêu";

                break;
            case 'wednesday':
                $weekday = 'T.4';

                $mt=array(array(180,"kh","khanh-hoa","Khánh Hòa","kh",31),array(176,"dn","da-nang","Đà nẵng","dng",30));
                $mn=array(array(163,"dn","dong-nai","Đồng Nai","dn",10),array(161,"ct","can-tho","Cần Thơ","ct",11),array(169,"st","soc-trang","Sóc Trăng","st",12));

                $mientrungName = "Khánh Hòa, Đà nẵng";
                $miennamName = "Đồng Nai, Cần Thơ, Sóc Trăng";

                break;
            case 'thursday':
                $weekday = 'T.5';

                $mt=array(array(175,"bd","binh-dinh","Bình Định","bdh",32),array(184,"qb","quang-binh","Quảng Bình","qb",33),array(187,"qt","quang-tri","Quảng Trị","qt",34));
                $mn=array(array(156,"ag","an-giang","An Giang","ag",14),array(170,"tn","tay-ninh","Tây Ninh","tn",13),array(159,"bt","binh-thuan","Bình Thuận","bth",15));

                $mientrung = "BDI,QB,QT";
                $miennam = "AG,TN,BTH";

                $mientrungName = "Bình Định,Quảng Bình,Quảng Trị";
                $miennamName = "An Giang,Tây Ninh,Bình Thuận";
                $mienbacName = "Hà Nội";
                break;
            case 'friday':
                $weekday = 'T.6';

                $mt=array(array(179,"gl","gia-lai","Gia Lai","gl",35),array(182,"nt","ninh-thuan","Ninh Thuận","nt",36));
                $mn=array(array(157,"bd","binh-duong","Bình Dương","bd",17),array(172,"tv","tra-vinh","Trà Vinh","tv",18),array(173,"vl","vinh-long","Vĩnh Long","vl",16));

                $mientrung = "GL,NT";
                $miennam = "BDU,TV,VL";

                $mientrungName = "Gia Lai,Ninh Thuận";
                $miennamName = "Bình Dương,Trà Vinh,Vĩnh Long";
                $mienbacName = "Hải Phòng";
                break;
            case 'saturday':
                $weekday = 'T.7';

                $mt=array(array(176,"dn","da-nang","Đà Nẵng","dng",30),array(178,"dn","dac-nong","Đắc Nông","dno",38),array(185,"qn","quang-ngai","Quảng Ngãi","qni",37));
                $mn=array(array(166,"hcm","ho-chi-minh","Hồ Chí Minh","hcm",1),array(168,"la","long-an","Long An","la",19),array(158,"bp","binh-phuoc","Bình Phước","bp",21),array(165,"hg","hau-giang","Hậu Giang","hg",20));

                $mientrung = "DNA,DNO,QNG";
                $miennam = "HCM,LA,BP,HG";

                $mientrungName = "Đà Nẵng,Đắc Nông,Quảng Ngãi";
                $miennamName = "Hồ Chí Minh,Long An,Bình Phước,Hậu Giang";
                $mienbacName = "Nam Định";
                break;
            default:
                $weekday = 'CN';

                $mt=array(array(181,"kt","kon-tum","Kon Tum","kt",39),array(180,"kh","khanh-hoa","Khánh Hoà","kh",31));
                $mn=array(array(171,"tg","tien-giang","Tiền Giang","tg",22),array(167,"kg","kien-giang","Kiên Giang","kg",23),array(162,"dl","da-lat","Đà Lạt","dl",24));

                $mientrung = "KT,KH";
                $miennam = "TG,KG,DLT";

                $mientrungName = "Kon Tum,Khánh Hoà";
                $miennamName = "Tiền Giang,Kiên Giang,Đà Lạt";
                $mienbacName = "Thái Bình";

                break;

        }
        return $mt;

    }
    public function  actionSoiCauMienBac()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date('Y-m-d');
        $day = date('w', strtotime ( '+1 day' , strtotime ( $date ) ));
        $time = date('H:i:s');
        $weekdayName = strtolower(date("l"));
        $lich = $this->actionLichQuay($weekdayName);

        if (Yii::$app->request->isPost) {
            // data post
            $request = Yii::$app->request;
            $soVang = Yii::$app->request->post('soVang');
            $soBac = Yii::$app->request->post('soBac');
            $kepVang = Yii::$app->request->post('kepVang');
            $xienDoi = Yii::$app->request->post('xienDoi');
            $dau = Yii::$app->request->post('dau');
            $duoi = Yii::$app->request->post('duoi');
            $danDacBiet = Yii::$app->request->post('danDacBiet');
            $bachThu = Yii::$app->request->post('bachThu');
            $baCangXanh = $request->post('baCangXanh');
            $data= array($soVang,$soBac,$kepVang,$xienDoi,$dau,$duoi,$danDacBiet,$bachThu,$baCangXanh);
            // check ngày tồn tại
            $checkNgay = Soicau::find()->where(['date'=>$date])->count();
            if ($checkNgay > 0 ){
                $model = Soicau::find()->where(['date'=>$date])->one();
                $model->province = 136;
                $model->create_date = date('Y-m-d H:i:s');
                $model->date = date('Y-m-d');
                $model->data = Json::encode($data);
                // ddataa ra json đang sai quy tắc
                $model->save();
            }else{
                $model = new Soicau();
                $model->province = 136;
                $model->create_date = date('Y-m-d H:i:s');
                $model->date = date('Y-m-d');
                $model->data = Json::encode($data);
                // ddata ra json đang sai quy tắc

                if ($model->save()){
                    $productjson = Json::encode($model);
                    $jsonfile = Yii::getAlias('@webroot/json/soi-cau-mien-bac.json');
                    $fp = fopen($jsonfile, 'w+');
                    fwrite($fp, $productjson);
                    fclose($fp);
                }
            }
        }

        /*
         * - Check lịch ngày hôm này//
         * - So sánh thứ mấy//
         * - dữ liệu sẽ lấy ra ở đâu
         * - Check giờ  thực hiện ghi dữ liệu ra json
         * -
         * -
         * */
        return $this->render('soi-cau-mien-bac');
    }


    /**
     * Finds the Result model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Result the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Result::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
