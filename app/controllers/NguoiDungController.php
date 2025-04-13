<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__.'/../models/DKsukien.php';

class NguoiDungController {
    public function DKSuKien() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $tenSK = $_POST['ten_sk'] ?? '';
            $loaiSK = $_POST['loai_sk'] ?? '';
            $tenND = $_POST['ten_kh'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $diaDiem = $_POST['noi_to_chuc'] ?? '';
            $ngayBD_raw = $_POST['ngay_bd'] ?? '';
            $ngayKT_raw = $_POST['ngay_kt'] ?? '';
            $soNguoi = $_POST['nguoi_tham_gia'] ?? 0;
            $ghiChu = $_POST['mo_ta'] ?? '';

            // Chuyển định dạng ngày tháng cho MySQL
            try {
                $ngayBD = (new DateTime($ngayBD_raw))->format('Y-m-d H:i:s');
                $ngayKT = (new DateTime($ngayKT_raw))->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                die("Lỗi định dạng ngày tháng.");
            }

            // Gọi model để thêm dữ liệu
            $model = new DKSuKien();
            $result = $model->themSuKien($tenSK, $loaiSK, $tenND, $sdt, $diaDiem, $ngayBD, $ngayKT, $soNguoi);

            if ($result) {
                // Lấy thêm thông tin về giá tiền dựa trên loại sự kiện
                $giaTien = $this->getGiaTien($loaiSK);
                
                // Lưu thông tin vào session
                $_SESSION['current_sk'] = [
                    'ma_sk' => $this->getLastInsertId(),
                    'ten_sk' => $tenSK,
                    'loai_sk' => $loaiSK,
                    'ten_kh' => $tenND,
                    'sdt' => $sdt,
                    'noi_to_chuc' => $diaDiem,
                    'ngay_bd' => $ngayBD,
                    'ngay_kt' => $ngayKT,
                    'nguoi_tham_gia' => $soNguoi,
                    'gia_tien' => $giaTien,
                    'mo_ta' => $ghiChu
                ];
                
                // Chuyển hướng đến trang thanh toán
                header("Location: /Quan_Ly_Su_Kien/app/views/ThanhToan/thanhtoan.php");
                exit();
            } else {
                echo "<script>alert('Đăng ký thất bại!');</script>";
            }
        }
    }
    
    // Phương thức lấy ID của sự kiện vừa thêm
    private function getLastInsertId() {
        global $conn;
        return $conn->insert_id;
    }
    
    // Phương thức lấy giá tiền dựa trên loại sự kiện
    private function getGiaTien($loaiSK) {
        $giaTien = [
            'hoinghi' => 15000000,
            'tieccuoi' => 45000000,
            'sinhnhat' => 5000000,
            'workshop' => 8500000,
            'hoithao' => 12000000,
            'teambuilding' => 20000000,
            'ramatsp' => 30000000,
            'khac' => 10000000
        ];
        
        return $giaTien[$loaiSK] ?? 10000000;
    }
}
?>