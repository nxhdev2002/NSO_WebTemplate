<?php
session_start();
$title = "Đổi mật khẩu";
require_once("config/config.php");
require_once "includes/header.php";
if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] === 0) {
    header("location: /login.php");
    die();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['oldpasswd']) && isset($_POST['newpasswd'])) {
    $oldpasswd = htmlspecialchars($_POST['oldpasswd']);
	$newpasswd = htmlspecialchars($_POST['newpasswd']);
    $conds = [
        'id' => $_SESSION['is_login'],
        'password' => $oldpasswd
    ];
    $condsQuery = 'id=:id AND password=:password';
    $data = [
        'password' => $newpasswd
    ];
    
    $result = $dbConn->update('player', $conds, $data, $condsQuery);
    if (!$result) {
        $error = "Mật khẩu sai!";
        echo "<script>
            showErrorDialog('Sai mật khẩu hiện tại. Bạn vui lòng kiểm tra lại nhé')
        </script>";
    } else {
        echo "<script>
            showAutoCloseDialog('Success', 'Đổi mật khẩu thành công');
        </script>";
    }
    
}
?>
<div class="login" style="background: url(assets/images/hero-bg1.webp); height: 780px">
    <div class="align-items-center" style="display: flex; height: 100%">
        <div class="container">
            <div class="row justify-content-center">
                <div class="login-form col-4" style="padding: 20px; border: solid 1px gray; border-radius: 25px">
                    <h1 style="font-family: PoppinsRegular">Đổi mật khẩu</h1>
                    <form method="post">
                        <div class="mb-3">
                            <label for="oldpasswd" class="form-label" style="font-family: PoppinsRegular">Mật khẩu cũ</label>
                            <input type="text" name="oldpasswd" class="form-control" id="oldpasswd" placeholder="Mật khẩu cũ..">
                        </div>
                        <div class="mb-3">
                            <label for="newpasswd" class="form-label" style="font-family: PoppinsRegular">Mật khẩu mới</label>
                            <input type="password" name="newpasswd" class="form-control" id="newpasswd" placeholder="Mật khẩu mới..">
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
