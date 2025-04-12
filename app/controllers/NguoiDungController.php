<?php
session_start();
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
            $ghiChu = $_POST['ghi_chu'] ?? '';

            // Chuyển định dạng ngày tháng cho MySQL
            try {
                $ngayBD = (new DateTime($ngayBD_raw))->format('Y-m-d H:i:s');
                $ngayKT = (new DateTime($ngayKT_raw))->format('Y-m-d H:i:s');
            } catch (Exception $e) {
                die("Lỗi định dạng ngày tháng.");
            }

            // Gọi model để thêm dữ liệu
            $model = new DKSuKien();
            $result = $model->themSuKien($tenSK, $loaiSK, $tenND, $sdt, $diaDiem, $ngayBD, $ngayKT, $soNguoi, $ghiChu);

            if ($result) {
                // lưu thông tin vào seesion
                $_SESSION['ten_sk'] = $tenSK;
                $_SESSION['ten_kh'] = $tenND;
                echo "<script>
                alert('Đăng ký thành công!');
                console.log(window.location.href); // sẽ in ra đường dẫn
                window.location.href = '/Quan_Ly_Su_Kien/app/views/ThanhToan/thanhtoan.php';
            </script>";
            
                    exit();
            } else {
                echo "<script>alert('Đăng ký thất bại!');</script>";
            }
        }
    }
}
?>
