<?php
require_once __DIR__ . '/../models/QuanLyModel.php';

class QuanLyController {
    private $model;
    
    public function __construct() {
        $this->model = new QuanLyModel();
    }
    
    // Lấy danh sách các sự kiện cho phần quản lý thanh toán
    public function getPaymentList($status = 'all', $search = '', $page = 1) {
        return $this->model->getAllEvents($status, $search, $page);
    }
    
    // Đếm tổng số sự kiện để phân trang
    public function countEvents($status = 'all', $search = '') {
        return $this->model->countEvents($status, $search);
    }
    
    // Lấy chi tiết sự kiện
    public function getEventDetail($eventId) {
        return $this->model->getEventDetail($eventId);
    }
    
    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($eventId, $status, $method = null, $note = null) {
        return $this->model->updatePaymentStatus($eventId, $status, $method, $note);
    }
    
    // Lấy thống kê doanh thu theo tháng
    public function getRevenueByMonth($year) {
        return $this->model->getRevenueByMonth($year);
    }
    
    // Lấy thống kê doanh thu theo năm
    public function getRevenueByYear() {
        return $this->model->getRevenueByYear();
    }
    
    // Lấy danh sách các năm
    public function getYearsList() {
        return $this->model->getYearsList();
    }
    
    // Lấy thống kê theo loại sự kiện
    public function getEventTypeStats($year = null) {
        return $this->model->getEventTypeStats($year);
    }
    
    // Lấy thống kê tổng quan
    public function getOverallStats() {
        return $this->model->getOverallStats();
    }
    
    // Format tiền tệ
    public function formatCurrency($amount) {
        return number_format($amount, 0, ',', '.') . ' VNĐ';
    }
    
    // Format ngày tháng
    public function formatDateTime($datetime) {
        if (empty($datetime)) return "N/A";
        $date = new DateTime($datetime);
        return $date->format('d/m/Y H:i');
    }
    
    // Cập nhật giá tiền cho sự kiện
    public function createOrUpdateEvent($eventData) {
        // TODO: Cập nhật dữ liệu cho sự kiện từ form quản lý
    }
    
    // Xuất báo cáo thống kê
    public function exportReport($type, $year = null) {
        // Logic xuất báo cáo theo định dạng CSV hoặc PDF
        $data = [];
        $filename = "bao_cao_";
        
        switch ($type) {
            case 'monthly':
                $data = $this->getRevenueByMonth($year);
                $filename .= "thang_" . $year . ".csv";
                break;
            case 'yearly':
                $data = $this->getRevenueByYear();
                $filename .= "nam.csv";
                break;
            case 'event_type':
                $data = $this->getEventTypeStats($year);
                $filename .= "loai_su_kien" . ($year ? "_" . $year : "") . ".csv";
                break;
            default:
                $data = $this->getOverallStats();
                $filename .= "tong_quan.csv";
        }
        
        // Chuyển đổi dữ liệu thành định dạng CSV và tải xuống
        $this->outputCSV($data, $filename);
    }
    
    // Hàm hỗ trợ để xuất file CSV
    private function outputCSV($data, $filename) {
        // Thiết lập headers cho file download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        // Tạo file pointer cho output
        $output = fopen('php://output', 'w');
        
        // Thêm BOM cho UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Nếu có dữ liệu, thêm tiêu đề và dữ liệu
        if (!empty($data)) {
            // Lấy tiêu đề từ khóa của mảng đầu tiên
            fputcsv($output, array_keys($data[0]));
            
            // Thêm dữ liệu từng dòng
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }
        
        fclose($output);
        exit;
    }
}