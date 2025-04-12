<?php
    include_once __DIR__."/../models/DKsukien.php";

    class NguoiDungController{
        private $DKsukienModel;

        public function __construct()
        {
            $this->DKsukienModel = new DKSuKien();
        }

        //xử lý đăng ký sự kiện
        public function DKSuKien(){
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $requireFields = ['ten_kh', 'sdt', 'noi_to_chuc', 'ten_sk', 'ngay_bd', 'ngay_kt', 'loai_sk', 'nguoi_tham_gia'];

                foreach($requireFields as $fields){
                    if(!isset($_POST[$fields]) || empty($_POST[$fields])){
                        die("Vui lòng điền đầy đủ thông tin!");
                    }
                }
                // lấy dữ liệu từ Form
                $ten_kh = $_POST['ten_kh'];
                $sdt = $_POST['sdt'];
                $noi_to_chuc = $_POST['noi_to_chuc'];
                $ten_sk = $_POST['ten_sk'];
                $ngay_bd = $_POST['ngay_bd'];
                $ngay_kt = $_POST['ngay_kt'];
                $loai_sk = $_POST['loai_sk'];
                $nguoi_tham_gia = $_POST['nguoi_tham_gia'];

                //thêm vào CSDL
                if($this->DKsukienModel->themSuKien($ten_sk, $loai_sk, $ten_kh, $sdt, $noi_to_chuc, $ngay_bd, $ngay_kt, $nguoi_tham_gia)){
                    //chuyển hướng đến trang thanh toán
                    $ma_sk = $this->DKsukienModel->getConn()->insert_id;
                    if ($ma_sk == 0) {
                        die("Không thể lấy mã sự kiện!");
                    }
                    header("Location: thanhtoan.php?ma_sk=$ma_sk");
                    exit();
                }
            }else{
                die("Đăng ký thất bại!");
            }
        }
    }

    //xử lý Request
    $controller = new NguoiDungController();
    
?>