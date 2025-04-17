<?php
require_once __DIR__ . '/../database.php';

class DKsukien {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Tính giá tiền dựa trên loại sự kiện và số người tham gia
    private function tinhGiaTien($loaiSK, $soNguoi) {
        $giaCoBan = 0;
        switch (strtolower($loaiSK)) {
            case 'sinhnhat':
                $giaCoBan = 2000000;
                break;
            case 'workshop':
                $giaCoBan = 5000000;
                break;
            case 'hoithao':
                $giaCoBan = 7000000;
                break;
            case 'teambuilding':
                $giaCoBan = 10000000;
                break;
            case 'ramatsp':
                $giaCoBan = 15000000;
                break;
            default:
                $giaCoBan = 3000000;
        }
        $giaThem = $soNguoi * 50000;
        return $giaCoBan + $giaThem;
    }

    public function themSuKien($tenSK, $loaiSK, $tenKH, $sdt, $noiToChuc, $ngayBD, $ngayKT, $nguoiThamGia) {
        $giaTien = $this->tinhGiaTien($loaiSK, $nguoiThamGia);
        $query = "INSERT INTO su_kien (TEN_SK, LOAI_SK, TEN_KH, SDT, NOI_TO_CHUC, NGAY_BD, NGAY_KT, NGUOI_THAM_GIA, GIA_TIEN) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssssdi", $tenSK, $loaiSK, $tenKH, $sdt, $noiToChuc, $ngayBD, $ngayKT, $nguoiThamGia, $giaTien);
        
        if ($stmt->execute()) {
            return $this->conn->insert_id; // Trả về ID của sự kiện vừa thêm
        }
        return false;
    }
}
?>