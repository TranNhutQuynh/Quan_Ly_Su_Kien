<?php
require_once __DIR__ . '/../models/DKsukien.php';

class NguoiDungController {
    private $model;

    public function __construct() {
        $this->model = new DKsukien();
    }

    public function DKSuKien() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenSK = $_POST['ten_sk'] ?? '';
            $loaiSK = $_POST['loai_sk'] ?? '';
            $tenKH = $_POST['ten_kh'] ?? '';
            $sdt = $_POST['sdt'] ?? '';
            $noiToChuc = $_POST['noi_to_chuc'] ?? '';
            $ngayBD = $_POST['ngay_bd'] ?? '';
            $ngayKT = $_POST['ngay_kt'] ?? '';
            $nguoiThamGia = $_POST['nguoi_tham_gia'] ?? 0;

            // Kiểm tra dữ liệu đầu vào
            if (empty($tenSK) || empty($loaiSK) || empty($tenKH) || empty($sdt) || empty($noiToChuc) || empty($ngayBD) || empty($ngayKT) || $nguoiThamGia <= 0) {
                return ['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin.'];
            }

            // Thêm sự kiện
            $result = $this->model->themSuKien($tenSK, $loaiSK, $tenKH, $sdt, $noiToChuc, $ngayBD, $ngayKT, $nguoiThamGia);
            if ($result) {
                $_SESSION['ma_sk'] = $result; // Lưu ID sự kiện vào session
                return ['success' => true, 'message' => 'Đăng ký sự kiện thành công!', 'ma_sk' => $result];
            }
            return ['success' => false, 'message' => 'Đăng ký sự kiện thất bại.'];
        }
        return ['success' => false, 'message' => 'Yêu cầu không hợp lệ.'];
    }
}
?>