<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

include "config/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= $_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.3/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?= $_URL ?>/assets/js/main.js"></script>
    <title><?= $title . " - " . $_DESCRIPTION ?></title>
</head>

<body>
    <div class="app">
        <nav class="navbar navbar-expand-lg fixed-top navbar-light" style="background: rgba(70,104,50,0.6);">
            <a class="navbar-brand" href="#">HVN</a>

            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?= $_URL ?>">Trang chủ <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Bảng xếp hạng
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Top Server</a>
                            <a class="dropdown-item" href="#">Top Bang</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Diễn Đàn</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên Hệ</a>
                    </li>
                </ul>
                <?php 
                if (isset($_SESSION['is_login']) && $_SESSION['is_login'] !== 0) {
                    $user = $dbConn->fetchRow('SELECT * FROM player WHERE id = :id', ['id' => $_SESSION['is_login']])
                ?>
                    <ul class="navbar-nav" style="align-self: flex-end;">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Xin chào, <?= $user['username'] ?>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="#"><i class="fa-solid fa-dong-sign"></i> <?= $user['luong'] ?></a>
                                <a class="dropdown-item" href="/home.php">Trang chủ</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/chpasswd.php">Đổi mật khẩu</a>
                                <a class="dropdown-item" href="/logout.php">Đăng xuất</a>
                            </div>
                        </li>
                    </ul>
                <?php
                    } else { 
                        echo '<a class="btn btn-primary" href="/login.php">Đăng nhập</a> <a class="btn btn-danger" href="/register.php">Đăng ký</a>';
                    }
                ?> 
            </div>

            <div>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

        </nav>