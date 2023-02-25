<?php
session_start();
$title = "Đăng nhập";
require_once("config/config.php");
require_once "includes/header.php";
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] === 0) {
    header("location: /login.php");
    die();
}
?>
<div class="container">
    <div class="webshop">
        <p style="font-family: PoppinsRegular;">Web Shop</p>
        <form action="" class="form-inline">
            <div class="form-group mx-sm-3 mb-2">
                <label for="searchkey" class="sr-only">Tìm kiếm vật phẩm</label>
                <input type="password" class="form-control" id="searchkey" placeholder="Tìm kiếm vật phẩm">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Tìm kiếm</button>
        </form>
        <div class="row">
            <?php
            $result = $dbConn->fetchRowMany('SELECT * FROM webshop');
            foreach ($result as $cursor) {
            ?>
                <div class="col-6 col-md-3">
                    <div class="item d-flex justify-content-space-between align-items-center flex-column">
                        <img src="<?= $_URL ?>/<?= $cursor['hinh_anh'] ?>" alt="">
                        <div class="detail"><?= $cursor['chi_tiet_webshop'] ?></div>
                        <div class="cost"><i class="fa-solid fa-dong-sign"></i></i><?= $cursor['gia_coin'] ?></div>
                        <div class="dropdown-divider"></div>
                        <button class="btn btn-success">Mua</button>
                    </div>
                </div>
            <?
            }

            ?>
        </div>
    </div>
</div>