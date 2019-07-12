<?php
/**
 * Created by nvdiepse.
 * User: NgoGiaDiep
 * Date: 6/25/2019
 * Time: 4:14 PM
 */
$this->title = 'Soi cầu miền bắc';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="box box-primary">
    <div class="box-header with-border">
    </div><!-- /.box-header -->
    <div class="box-body">
        <form action="soi-cau-mien-bac" method="POST">
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <table class="table table-responsive table-striped">
                <h4>Số Đẹp - Miền Bắc  </h4>
                <tr>
                    <td><b>+ Số vàng</b></td>
                    <td><input type="text" name="soVang"></td>
                </tr><tr>
                    <td><b>+ Số bạc</b></td>
                    <td><input type="text" name="soBac"></td>
                </tr><tr>
                    <td><b>+ Kép vàng</b></td>
                    <td><input type="text" name="kepVang"></td>
                </tr><tr>
                    <td><b>+ Xiên đôi</b></td>
                    <td><input type="text" name="xienDoi"></td>
                </tr>
            </table>
            <table class="table table-responsive table-striped">
                <h4>Phân Tích Đặc Biệt </h4>
                <tr>
                    <td><b>+ Đầu</b></td>
                    <td><input type="text" name="dau"></td>
                </tr>
                <tr>
                    <td><b>+ Đuôi</b></td>
                    <td><input type="text" name="duoi"></td>
                </tr>
                <tr>
                    <td><b>+ Dàn Đặc Biệt</b></td>
                    <td><input type="text" name="danDacBiet" id="danDacBiet"></td>
<!--                    <td>-->
<!--                        <div class="alert alert-danger" role="alert" style="display: none;" id="danDacBiet">-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td><b>+ Bạch Thủ</b></td>
                    <td><input type="text" name="bachThu" id="bachThu"></td>
<!--                    <td>-->
<!--                        <div class="alert alert-danger" role="alert" style="display: none;" id="bachThu">-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
                <tr>
                    <td><b>+ 3 Càng Sáng</b></td>
                    <td>
                        <input type="text" name="baCangXanh" id="inputBaCangXanh">
                    </td>
<!--                    <td>-->
<!--                        <div class="alert alert-danger" role="alert" style="display: none;" id="baCangXanh">-->
<!--                        </div>-->
<!--                    </td>-->
                </tr>
            </table>
            <table class="table table-reponsive table-striped">
                <tr>
                    <th>Ngày</th>
                    <th>Số đẹp</th>
                    <th>Cầu vàng</th>
                    <th>Số trúng</th>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                    <td><input type="text" value=""></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <input type="submit" class="btn btn-success" value="Cập nhật" >
        </form>
    </div>
</div>
