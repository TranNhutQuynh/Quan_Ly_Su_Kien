<?php
require_once __DIR__ . '/../database.php';

class QuanLyModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Lấy tất cả sự kiện với thông tin thanh toán
    public function getAllEvents($status = 'all', $search = '', $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $whereClause = '';
        
        // Xử lý điều kiện lọc theo trạng thái
        if ($status !== 'all') {
            $statusValue = ($status === 'paid') ? "Đã thanh toán" : "Chưa thanh toán";
            $whereClause .= " AND TT.TRANG_THAI = '$statusValue'";
        }
        
        // Xử lý tìm kiếm
        if (!empty($search)) {
            $search = "%{$search}%";
            $whereClause .= " AND (SK.TEN_SK LIKE ? OR SK.TEN_KH LIKE ? OR SK.MA_SK = ?)";
        }

        $query = "SELECT SK.*, TT.TRANG_THAI, TT.PHUONG_THUC
                 FROM su_kien SK 
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 WHERE 1=1 $whereClause
                 ORDER BY SK.NGAY_BD DESC
                 LIMIT ?, ?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchId = is_numeric($search) ? intval(str_replace('%', '', $search)) : 0;
            $stmt->bind_param("ssiii", $search, $search, $searchId, $offset, $limit);
        } else {
            $stmt->bind_param("ii", $offset, $limit);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
        
        return $events;
    }
    
    // Đếm tổng số sự kiện (cho phân trang)
    public function countEvents($status = 'all', $search = '') {
        $whereClause = '';
        
        if ($status !== 'all') {
            $statusValue = ($status === 'paid') ? "Đã thanh toán" : "Chưa thanh toán";
            $whereClause .= " AND TT.TRANG_THAI = '$statusValue'";
        }
        
        if (!empty($search)) {
            $search = "%{$search}%";
            $whereClause .= " AND (SK.TEN_SK LIKE ? OR SK.TEN_KH LIKE ? OR SK.MA_SK = ?)";
        }

        $query = "SELECT COUNT(*) as total 
                 FROM su_kien SK 
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 WHERE 1=1 $whereClause";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchId = is_numeric($search) ? intval(str_replace('%', '', $search)) : 0;
            $stmt->bind_param("ssi", $search, $search, $searchId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row['total'];
    }
    
    // Lấy chi tiết một sự kiện
    public function getEventDetail($eventId) {
        $query = "SELECT SK.*, TT.TRANG_THAI, TT.DA_THANH_TOAN, TT.PHUONG_THUC
                 FROM su_kien SK 
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 WHERE SK.MA_SK = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($eventId, $status, $method = null, $note = null) {
        // Kiểm tra xem bản ghi thanh toán đã tồn tại chưa
        $checkQuery = "SELECT * FROM thanh_toan WHERE MA_SK = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("i", $eventId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        $trangThai = ($status == 1) ? 'Đã thanh toán' : 'Chưa thanh toán';
        $daThanhToan = $status;
        
        if ($result->num_rows > 0) {
            // Cập nhật bản ghi hiện có
            $query = "UPDATE thanh_toan 
                     SET TRANG_THAI = ?, 
                         DA_THANH_TOAN = ?,
                         PHUONG_THUC = ?
                     WHERE MA_SK = ?";
                     
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sissi", $trangThai, $daThanhToan, $method, $note, $eventId);
        } else {
            // Tạo bản ghi mới
            $query = "INSERT INTO thanh_toan (MA_SK, TRANG_THAI, DA_THANH_TOAN, PHUONG_THUC) 
                     VALUES (?, ?, ?, ?)";
                     
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("isiss", $eventId, $trangThai, $daThanhToan, $method, $note);
        }
        
        return $stmt->execute();
    }
    
    // Thống kê doanh thu theo tháng
    public function getRevenueByMonth($year) {
        $query = "SELECT 
                    MONTH(SK.NGAY_BD) as thang,
                    SUM(CASE WHEN TT.TRANG_THAI = 'Đã thanh toán' THEN TT.TONG_TIEN_TT ELSE 0 END) as doanh_thu,
                    COUNT(DISTINCT SK.MA_SK) as so_su_kien,
                    COUNT(DISTINCT SK.TEN_KH) as so_khach_hang
                 FROM su_kien SK
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 WHERE YEAR(SK.NGAY_BD) = ?
                 GROUP BY MONTH(SK.NGAY_BD)
                 ORDER BY MONTH(SK.NGAY_BD)";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $year);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $stats = [];
        
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        
        return $stats;
    }
    
    // Thống kê doanh thu theo năm
    public function getRevenueByYear() {
        $query = "SELECT 
                    YEAR(SK.NGAY_BD) as nam,
                    SUM(CASE WHEN TT.TRANG_THAI = 'Đã thanh toán' THEN SK.GIA_TIEN ELSE 0 END) as doanh_thu,
                    COUNT(DISTINCT SK.MA_SK) as so_su_kien,
                    COUNT(DISTINCT SK.TEN_KH) as so_khach_hang
                 FROM su_kien SK
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 GROUP BY YEAR(SK.NGAY_BD)
                 ORDER BY YEAR(SK.NGAY_BD)";
                 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $stats = [];
        
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        
        return $stats;
    }
    
    // Lấy danh sách các năm có trong dữ liệu
    public function getYearsList() {
        $query = "SELECT DISTINCT YEAR(NGAY_BD) as year FROM su_kien ORDER BY year DESC";
        $result = $this->conn->query($query);
        
        $years = [];
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['year'];
        }
        
        return $years;
    }
    
    // Lấy thống kê loại sự kiện
    public function getEventTypeStats($year = null) {
        $whereClause = '';
        if ($year !== null) {
            $whereClause = " WHERE YEAR(NGAY_BD) = $year";
        }
        
        $query = "SELECT 
                SK.LOAI_SK as loai, 
                COUNT(*) as so_luong,
                SUM(TT.TONG_TIEN_TT) as tong_gia_tri
             FROM su_kien SK
             LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
             $whereClause
             GROUP BY SK.LOAI_SK
             ORDER BY COUNT(*) DESC";
                 
        $result = $this->conn->query($query);
        
        $stats = [];
        while ($row = $result->fetch_assoc()) {
            $stats[] = $row;
        }
        
        return $stats;
    }
    
    // Lấy tổng số lượng sự kiện, doanh thu, khách hàng
    public function getOverallStats() {
        $query = "SELECT 
                    (SELECT COUNT(*) FROM su_kien) as total_events,
                    (SELECT COUNT(DISTINCT TEN_KH) FROM su_kien) as total_customers,
                    (SELECT SUM(TT.TONG_TIEN_TT) FROM su_kien SK 
                     INNER JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK 
                     WHERE TT.TRANG_THAI = 'Đã thanh toán') as total_revenue,
                    (SELECT COUNT(*) FROM su_kien SK 
                     LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK 
                     WHERE TT.TRANG_THAI = 'Chưa thanh toán' OR TT.TRANG_THAI IS NULL) as pending_payments,
                    (SELECT COUNT(*) FROM su_kien 
                     WHERE NGAY_BD > NOW()) as upcoming_events";
                     
        $result = $this->conn->query($query);
        return $result->fetch_assoc();
    }
}