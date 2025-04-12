<?php
    // app/models/sukien.php
    include_once __DIR__ . "/../../config/database.php"; //đảm bảo kết nối CSDL

    class DKSuKien{
        private $conn;

        //hàm khởi tạo
        public function __construct(){
            global $conn;
            $this->conn = $conn;
        }

        public function getConn(){
            return $this->conn;
        }

        //lấy tất cả sự kiện
        public function getAllEvent(){
            $sql = "SELECT * FROM su_kien";
            $result = $this->conn->query($sql);
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        // THÊM SỰ KIỆN MỚI
        public function themSuKien($ten_sk, $loai_sk, $ten_kh, $sdt, $noi_to_chuc, $ngay_bd, $ngay_kt, $nguoi_tham_gia) {
            $sql = "INSERT INTO su_kien(TEN_SK,LOAI_SK,TEN_KH,SDT,NOI_TO_CHUC,NGAY_BD,NGAY_KT,NGUOI_THAM_GIA) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            if (!$stmt) {
                die("Lỗi chuẩn bị câu lệnh: " . $this->conn->error);
            }
            $stmt->bind_param("sssssssi", $ten_sk, $loai_sk, $ten_kh, $sdt, $noi_to_chuc, $ngay_bd, $ngay_kt, $nguoi_tham_gia);
            if ($stmt->execute()) {
                return true;
            } else {
                die("Lỗi thực thi câu lệnh: " . $stmt->error);
            }
        }
    }
?>