<?php 
    require_once("../config/config.php");
    require_once("../config/function.php");

    if(isset($_GET['phone']) && isset($_GET['code']) && isset($_SESSION['username']))
    {
        $userName = $getUser['username'];
        $code = check_string($_GET['code']);
        $phone = '+' . check_string($_GET['phone']);
        $order = $CMSNT->get_row(" SELECT * FROM `orders` WHERE `code` = '$code' AND `username` = '$userName' ");
        
        if(!$order)
        {
            die('Đơn hàng không tồn tại trong hệ thống!');
        }

        $account = $CMSNT->get_row(" SELECT * FROM `taikhoan` WHERE `code` = '$code' AND `chitiet` LIKE '$phone%' ");

        if(!$account)
        {
            die('Bạn không thể tải tệp này!');
        }
        
        $file = $phone . ".zip";
        if(!file_exists($file))
        { 
            die('Không có tệp để tải về!');
        }
        else
        {
            /* THÊM NHẬT KÝ */
            $CMSNT->insert("logs", [
                'username'  => $userName,
                'content'   => 'Tải xuống tệp #' . $code . ' ' . $phone,
                'createdate'=> gettime(),
                'time'      => time()
            ]);
            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$file");
            header("Content-Type: application/html");
            header("Content-Transfer-Encoding: binary");
            readfile($file);
        }
    }
