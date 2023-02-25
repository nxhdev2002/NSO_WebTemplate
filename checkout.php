<?php

function checkout()
{
    session_start();
    require_once './config/config.php';
    $result = new stdClass;
    $result->success = null;
    $result->message = null;
    if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] < 1) {
        $result->success = false;
        $result->message = "Bạn phải đăng nhập để mua hàng";
        return $result;
    }
    if (!isset($_POST['id']) || !isset($_POST['quantity'])) {
        $result->success = false;
        $result->message = "Thông tin hàng và số lượng không được để trống";
        return $result;
    }
    $itemId = $_POST['id'];
    $itemQuantity = (int)$_POST['quantity'];
    if (!is_int($itemQuantity)) {
        $result->success = false;
        $result->message = "Số lượng hàng phải là số nguyên";
        return $result;
    }
    $item = $dbConn->fetchRow('SELECT id, ten_vat_pham, gia_coin FROM webshop WHERE id = :id', ['id' => $itemId]);
    if ($item === NULL) {
        $result->success = false;
        $result->message = "Mặt hàng không tồn tại";
        return $result;
    }
    $amount = $item['gia_coin'] * $itemQuantity;
    $user = $dbConn->fetchRow('SELECT * FROM player WHERE id = :id', ['id' => $_SESSION['is_login']]);
    if ($user['coin'] < $amount) {
        $result->success = false;
        $result->message = "Bạn không có đủ tiền để mua mặt hàng này";
        return $result;
    }
    /// trừ tiền user
    $conds = [
        'id' => $user['id'],
    ];
    // custom conditions query
    $condsQuery = 'id = :id';

    $data = [
        'coin' => $user['coin'] - $amount
    ];
    $res = $dbConn->update('player', $conds, $data, $condsQuery);
    if ($res) {
        /// update vật phẩm
        $time = date("Y-m-d h:i:s");
        $data = [
            'player_id' => $user['id'],
            'ten_vat_pham' => $item['ten_vat_pham'],
            'coin' => $amount,
            'created_at' => $time
        ];
        if (!$dbConn->insert('history_webshop', $data)) {
            $result->success = false;
            $result->message = "Đã xảy ra lỗi khi nhận hàng. Vui lòng liên hệ Admin";
            return $result;
        } else {
            $result->success = true;
            $result->message = "Mua hàng thành công!";
            return $result;
        }
    } else {
        $result->success = false;
        $result->message = "Đã xảy ra lỗi khi thanh toán.";
        return $result;
    }
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    echo json_encode(checkout());
} else {
    header('location: /');
}
