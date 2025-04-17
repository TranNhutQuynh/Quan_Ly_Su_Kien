<?php
require_once __DIR__."/../models/thanhtoanModel.php";

class ThanhToanController {
    private $model;
    
    public function __construct() {
        $this->model = new thanhtoanModel();
    }
    
    public function thongtinTT() {
        if (isset($_SESSION['ma_sk'])) {
            $maSK = $_SESSION['ma_sk'];
            $suKien = $this->model->getSuKien($maSK);
            
            // Tính toán giá tiền dựa trên loại sự kiện và số người tham gia
            $giaTien = $this->tinhGiaTien($suKien['LOAI_SK'], $suKien['NGUOI_THAM_GIA']);
            $suKien['gia_tien'] = $giaTien;
            
            return $suKien;
        }
        return false;
    }
    
    public function tinhGiaTien($loaiSK, $soNguoi) {
        // Giả sử tính toán giá tiền theo loại sự kiện và số người
        $giaCoBan = 0;
        switch(strtolower($loaiSK)) {
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
        
        // Tính thêm chi phí theo số người tham gia
        $giaThem = $soNguoi * 50000;
        
        return $giaCoBan + $giaThem;
    }
    
    public function formatCurrency($amount) {
        return number_format($amount, 0, ',', '.') . ' VND';
    }
    
    public function formatDateTime($dateTime) {
        $date = new DateTime($dateTime);
        return $date->format('d/m/Y H:i');
    }
    
    // Trong ThanhToanController.php - phương thức xuLyThanhToan()
public function xuLyThanhToan() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ma_sk'])) {
        $maSK = $_POST['ma_sk'];
        $phuongThuc = $_POST['phuong_thuc'] ?? NULL;
        $trangThai = $_POST['trang_thai'] ?? 'Đã thanh toán';
        
        $suKien = $this->model->getSuKien($maSK);
        $giaTien = $this->tinhGiaTien($suKien['LOAI_SK'], $suKien['NGUOI_THAM_GIA']);
        
        // Kiểm tra xem đã có bản ghi thanh toán chưa
        $thanhToan = $this->model->getThanhToanInfo($maSK);
        
        if ($thanhToan) {
            // Cập nhật trạng thái thanh toán và giữ nguyên số tiền
            $result = $this->model->updateThanhToan($maSK, $trangThai, $phuongThuc);
        } else {
            // Tạo mới bản ghi thanh toán với giá tiền đã tính
            $result = $this->model->createThanhToan($maSK, $suKien['TEN_KH'], $giaTien, $trangThai, $phuongThuc);
        }
        
        if ($result) {
            return true;
        }
    }
    return false;
}
    
    public function getAllThanhToan() {
        return $this->model->getAllThanhToan();
    }
}
?>