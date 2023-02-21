<?php
session_start();
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== 1) {
    $title = "NSO";
    require_once("config/config.php");
    require_once("./includes/header.php");
?>
    <section style="background: url(https://htmldemo.net/bonx/bonx/assets/img/bg/hero-bg1.webp); height: 780px">
        <div class="overlay" style="background: rgba(70,104,50,0.6); height: 100%">
            <div class="align-items-center" style="display: flex; height: 100%">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h1 style="font-family: PoppinsRegular; font-size: 60px">HVN COMEBACK</h1>
                            <p style="font-size: 18px; font-family: PoppinsRegular ">Tham gia thế giới nhẫn giả và hoá thân thành ninja nắm giữ sức mạnh nguyên tố ngay bây giờ!</p>
                            <div class="btn btn-success"><img src="https://cdn-icons-png.flaticon.com/512/1185/1185771.png" width="25" height="25"> Play Now</div>
                        </div>
                        <div class="col-6">
                            <img src="<?= $_URL ?>/assets/images/3.png" width="600px" style="border-radius: 25px">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section style="background: url(<?= $_URL ?>/assets/images/3.png) no-repeat; background-size: 100%; height: 780px">
        <div class="overlay" style="background: rgba(70,104,50,0.6); height: 100%">
            <div class="align-items-center" style="display: flex; height: 100%">
                <div class="container">
                    <div class="row">
                        <div class="col-12 justify-content-center" style="display: flex">
                            <h1>Hệ thống Class đa dạng</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
}
?>