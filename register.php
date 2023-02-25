<?php
session_start();
$title = "Đăng ký";
require_once "includes/header.php";
require_once("config/config.php");
if (isset($_SESSION['is_login']) && $_SESSION['is_login'] !== 0) {
    header("location: /home.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['re-password'])) {
    $uname = htmlspecialchars($_POST['username']);
	$passw = htmlspecialchars($_POST['password']);
    $repassw = htmlspecialchars($_POST['re-password']);
    if ($uname == NULL || $passw == NULL || $repassw == NULL) 
        $error = 'Vui lòng điền đủ thông tin!.';
    else if (!preg_match('/^[a-zA-Z0-9]+$/',$uname.$passw))
        $error = 'Tài khoản mật khẩu không cho phép ký tự đặc biệt!.';
    else if ($passw != $repassw)
        $error = 'Mật khẩu phải khớp khi nhập lại!.';
    else if (strlen($uname) < 5 || strlen($uname) > 12 || strlen($passw) < 1 || strlen($passw) > 40)
        $error = 'Tài khoản phải từ 5 đến 12 mật khẩu phải từ 1 đến 40 ký tự!.';
    else if ($dbConn->fetchRow('SELECT username FROM player WHERE username = :name', ['name' => $uname]))
        $error = 'Tên tài khoản đã tồn tại!.';
    else {
        $dbConn->insert('player', [
            'username' => $uname,
            'password' => $passw,
            'luong' => 0
        ]);
        $_SESSION['is_login'] = 1;
        $_SESSION['username'] = $uname;
        echo "<script>
            showAutoCloseDialog('Success', 'Đăng ký thành công', () => {
                window.location = '/index.php';
            });
        </script>";
    }
    
}

?>
<div class="login" style="background: url(https://htmldemo.net/bonx/bonx/assets/img/bg/hero-bg1.webp); height: 780px">
    <div class="align-items-center" style="display: flex; height: 100%">
        <div class="container">
            <div class="row justify-content-center">
                <div class="register-form col-4" style="padding: 20px; border: solid 1px gray; border-radius: 25px">
                    <h1 style="font-family: PoppinsRegular">Đăng ký</h1>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label" style="font-family: PoppinsRegular">Tên người dùng</label>
                            <input type="text" name="username" class="form-control" id="username" aria-describedby="username" placeholder="Tên người dùng..">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label" style="font-family: PoppinsRegular">Password</label>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Mật khẩu..">
                        </div>
                        <div class="mb-3">
                            <label for="re-password" class="form-label" style="font-family: PoppinsRegular">Nhập lại mật khẩu</label>
                            <input type="password" name="re-password" class="form-control" id="re-password" placeholder="Nhập lại mật khẩu..">
                        </div>
                        <?php 
                            if (isset($error)) {
                                echo $error."<br>";
                                echo "<script>
                                    showErrorDialog('".$error."');
                                </script>";
                            }
                        ?>
                        <button type="submit" class="btn btn-primary">Đăng ký</button>
                        <a href="forgot_pwd.php" style="color: gray">Quên mật khẩu?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    require_once("./includes/footer.php");
