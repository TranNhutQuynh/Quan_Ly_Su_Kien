<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// DKsukien.php
require_once __DIR__ . '/../database.php'; // Đảm bảo đúng đường dẫn

class DKsukien {
    private $conn;

    public function __construct() {
        global $conn; // Lấy biến kết nối từ database.php
        $this->conn = $conn;
    }

    public function themSuKien($ten_sk, $loai_sk, $ten_kh, $sdt, $noi_to_chuc, $ngay_bd, $ngay_kt, $nguoi_tham_gia) {
        $query = "INSERT INTO su_kien (TEN_SK, LOAI_SK, TEN_KH, SDT, NOI_TO_CHUC, NGAY_BD, NGAY_KT, NGUOI_THAM_GIA)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("sssssssi", $ten_sk, $loai_sk, $ten_kh, $sdt, $noi_to_chuc, $ngay_bd, $ngay_kt, $nguoi_tham_gia);

        return $stmt->execute();
    }
}