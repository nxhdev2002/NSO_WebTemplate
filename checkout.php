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
    $item = $dbConn->fetchRow('SELECT * FROM webshop WHERE id = :id', ['id' => $itemId]);
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
    
    /// lấy thông tin item
    $itemDetail = json_decode($item['chi_tiet_game'], true);
    /// lấy danh sách ninja và kho đồ
    $ninja_list = json_decode($user['ninja'], true);
    if (count($ninja_list) < 1) {
        $result->success = false;
        $result->message = "Bạn tạo nhân vật trong game trước đã :D.";
        return $result;
    }
    $ninja = $dbConn->fetchRow('SELECT * FROM ninja WHERE name = :name LIMIT 1', ['name' => $ninja_list[0]]);
    $bag = json_decode($ninja['ItemBag'], true);
    /// kiểm tra đồ đã có trong kho đồ chưa
    foreach ($bag as $key => $value) {
        if ($value['id'] === $itemDetail['id']) {
            $index = $key;
        }
    }
    $enough = true;
    if (isset($index)) {
        if ((int)$ninja['maxluggage'] < count($bag)) {
            $enough = false;
        } 
    } else {
        if ((int)$ninja['maxluggage'] < count($bag) + 1) {
            $enough = false;
        }
    }
    if (!$enough) {
        $result->success = false;
        $result->message = "Bạn không đủ chỗ trong hành trang. Dọn sạch nó trước đã!";
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
        /// update lịch sử
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
            /// update vật phẩm
            if (isset($index)) {
                $bag[$index]['quantity'] += $itemDetail['quantity'] * $itemQuantity;
                foreach ($bag as $key => $value) {
                    $bag[$key]['index'] = $key;
                }
                $conds = [
                    'id'   => $ninja['id'],
                ];
                $condsQuery = 'id = :id';
                $data = [
                    'ItemBag' => json_encode($bag),
                ];
                if ($dbConn->update('ninja', $conds, $data, $condsQuery)) {
                    $result->success = true;
                    $result->message = "Mua hàng thành công!";
                    return $result;
                }
            } else {
                $itemDetail['expires'] = explode(".", (microtime_float() * 1000 + $itemDetail['expires']))[0];
                array_push($bag, $itemDetail);
                /// reset index trong túi
                foreach ($bag as $key => $value) {
                    $bag[$key]['index'] = $key;
                }
                $conds = [
                    'id'   => $ninja['id'],
                ];
                $condsQuery = 'id = :id';
                $data = [
                    'ItemBag' => json_encode($bag),
                ];
                if ($dbConn->update('ninja', $conds, $data, $condsQuery)) {
                    $result->success = true;
                    $result->message = "Mua hàng thành công!";
                    return $result;
                }
            }
        }
    } else {
        $result->success = false;
        $result->message = "Đã xảy ra lỗi khi thanh toán.";
        return $result;
    }
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


if ($_SERVER['REQUEST_METHOD'] === "POST") {
    echo json_encode(checkout());
} else {
    header('location: /');
}
