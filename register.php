<?php
session_start();
$title = "Đăng nhập";
require_once("config/config.php");
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === 1) {
    header("location: /home.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $user = htmlspecialchars($_POST['username']);
	$pass = htmlspecialchars($_POST['password']);
    $result = $dbConn->fetchColumn('SELECT password FROM player WHERE username = :name', ['name' => $user]);
    if ($result === NULL) {
        $error = "Tên đăng nhập không tồn tại!";
    } else if ($result !== $pass) {
        $error = "Sai mật khẩu. Vui lòng thử lại";
    } else {
        $_SESSION['is_login'] = 1;
        $_SESSION['username'] = $user;
        header("location: /index.php");
    }
    
}
require_once "includes/header.php";
?>
<div class="login" style="background: url(https://htmldemo.net/bonx/bonx/assets/img/bg/hero-bg1.webp); height: 780px">
    <div class="align-items-center" style="display: flex; height: 100%">
        <div class="container">
            <div class="row justify-content-center">
                <div class="login-form col-4" style="padding: 20px; border: solid 1px gray; border-radius: 25px">
                    <h1 style="font-family: PoppinsRegular">Đăng nhập</h1>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label" style="font-family: PoppinsRegular">Tên người dùng</label>
                            <input type="text" name="username" class="form-control" id="username" aria-describedby="username" placeholder="Tên người dùng..">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label" style="font-family: PoppinsRegular">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Mật khẩu..">
                        </div>
                        <?php 
                            if (isset($error)) {
                                echo $error."<br>";
                            }
                        ?>
                        <button type="submit" class="btn btn-primary">Đăng nhập</button>
                        <a href="forgot_pwd.php" style="color: gray">Quên mật khẩu?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once("./includes/footer.php");
