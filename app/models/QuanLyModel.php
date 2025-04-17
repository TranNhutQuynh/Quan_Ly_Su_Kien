<?php
require_once __DIR__ . '/../database.php';

class QuanLyModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Tính giá tiền dựa trên loại sự kiện và số người tham gia
    public function tinhGiaTien($loaiSK, $soNguoi) {
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

    // Cập nhật giá tiền vào bảng su_kien
    public function updateGiaTien($maSK, $giaTien) {
        $query = "UPDATE su_kien SET GIA_TIEN = ? WHERE MA_SK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("di", $giaTien, $maSK);
        return $stmt->execute();
    }

    // Lấy tất cả sự kiện với thông tin thanh toán
    public function getAllEvents($status = 'all', $search = '', $page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $whereClause = '';
        
        if ($status !== 'all') {
            $statusValue = ($status === 'paid') ? "Đã thanh toán" : "Chưa thanh toán";
            $whereClause .= " AND TT.TRANG_THAI = '$statusValue'";
        }
        
        if (!empty($search)) {
            $search = "%{$search}%";
            $whereClause .= " AND (SK.TEN_SK LIKE ? OR SK.TEN_KH LIKE ? OR SK.MA_SK = ?)";
        }

        $query = "SELECT SK.*, TT.TRANG_THAI, TT.PHUONG_THUC, TT.NGAY_THANH_TOAN, TT.TONG_TIEN_TT
                 FROM su_kien SK 
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 WHERE 1=1 $whereClause
                 ORDER BY SK.NGAY_BD DESC
                 LIMIT ?, ?";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $searchId = is_numeric(str_replace('%', '', $search)) ? intval(str_replace('%', '', $search)) : 0;
            $stmt->bind_param("ssiii", $search, $search, $searchId, $offset, $limit);
        } else {
            $stmt->bind_param("ii", $offset, $limit);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $events = [];
        while ($row = $result->fetch_assoc()) {
            if (empty($row['GIA_TIEN'])) {
                $row['GIA_TIEN'] = $this->tinhGiaTien($row['LOAI_SK'], $row['NGUOI_THAM_GIA']);
                $this->updateGiaTien($row['MA_SK'], $row['GIA_TIEN']);
            }
            $row['TONG_TIEN_TT'] = $row['GIA_TIEN'];
            $events[] = $row;
        }
        
        return $events;
    }

    // Đếm tổng số sự kiện
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
            $searchId = is_numeric(str_replace('%', '', $search)) ? intval(str_replace('%', '', $search)) : 0;
            $stmt->bind_param("ssi", $search, $search, $searchId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    // Cập nhật trạng thái thanh toán
    public function updatePaymentStatus($eventId, $status, $method = null) {
        $trangThai = ($status == 1) ? 'Đã thanh toán' : 'Chưa thanh toán';
        $ngayThanhToan = ($status == 1) ? date('Y-m-d') : null;

        $checkQuery = "SELECT * FROM thanh_toan WHERE MA_SK = ?";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bind_param("i", $eventId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            $query = "UPDATE thanh_toan 
                     SET TRANG_THAI = ?, PHUONG_THUC = ?, NGAY_THANH_TOAN = ?
                     WHERE MA_SK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $trangThai, $method, $ngayThanhToan, $eventId);
        } else {
            $event = $this->getEventDetail($eventId);
            $tongTien = $this->tinhGiaTien($event['LOAI_SK'], $event['NGUOI_THAM_GIA']);
            $query = "INSERT INTO thanh_toan (MA_SK, KHACH_HANG, TONG_TIEN_TT, TRANG_THAI, PHUONG_THUC, NGAY_THANH_TOAN) 
                     VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("isdsss", $eventId, $event['TEN_KH'], $tongTien, $trangThai, $method, $ngayThanhToan);
        }
        
        return $stmt->execute();
    }

    // Lấy chi tiết sự kiện
    public function getEventDetail($eventId) {
        $query = "SELECT SK.*, TT.TRANG_THAI, TT.PHUONG_THUC, TT.NGAY_THANH_TOAN, TT.TONG_TIEN_TT
                 FROM su_kien SK 
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 WHERE SK.MA_SK = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $eventId);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Thống kê số lượng sự kiện theo tháng, năm
    public function countEventsByMonthYear($month = null, $year = null) {
        $whereClause = '';
        $params = [];
        $types = '';

        if ($month !== null) {
            $whereClause .= " AND MONTH(SK.NGAY_BD) = ?";
            $params[] = $month;
            $types .= 'i';
        }
        if ($year !== null) {
            $whereClause .= " AND YEAR(SK.NGAY_BD) = ?";
            $params[] = $year;
            $types .= 'i';
        }

        $query = "SELECT YEAR(SK.NGAY_BD) AS nam, MONTH(SK.NGAY_BD) AS thang, COUNT(*) AS so_luong
                 FROM su_kien SK
                 WHERE 1=1 $whereClause
                 GROUP BY YEAR(SK.NGAY_BD), MONTH(SK.NGAY_BD)
                 ORDER BY nam DESC, thang DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Thống kê số lượng sự kiện theo loại, tháng, năm
    public function countEventsByTypeMonthYear($month = null, $year = null) {
        $whereClause = '';
        $params = [];
        $types = '';

        if ($month !== null) {
            $whereClause .= " AND MONTH(SK.NGAY_BD) = ?";
            $params[] = $month;
            $types .= 'i';
        }
        if ($year !== null) {
            $whereClause .= " AND YEAR(SK.NGAY_BD) = ?";
            $params[] = $year;
            $types .= 'i';
        }

        $query = "SELECT YEAR(SK.NGAY_BD) AS nam, MONTH(SK.NGAY_BD) AS thang, SK.LOAI_SK, COUNT(*) AS so_luong
                 FROM su_kien SK
                 WHERE 1=1 $whereClause
                 GROUP BY YEAR(SK.NGAY_BD), MONTH(SK.NGAY_BD), SK.LOAI_SK
                 ORDER BY nam DESC, thang DESC, SK.LOAI_SK";

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Thống kê doanh thu theo tháng, năm (chỉ tính sự kiện đã thanh toán)
    public function revenueByMonthYear($month = null, $year = null) {
        $whereClause = " AND TT.TRANG_THAI = 'Đã thanh toán'";
        $params = [];
        $types = '';

        if ($month !== null) {
            $whereClause .= " AND MONTH(SK.NGAY_BD) = ?";
            $params[] = $month;
            $types .= 'i';
        }
        if ($year !== null) {
            $whereClause .= " AND YEAR(SK.NGAY_BD) = ?";
            $params[] = $year;
            $types .= 'i';
        }

        $query = "SELECT YEAR(SK.NGAY_BD) AS nam, MONTH(SK.NGAY_BD) AS thang, SUM(SK.GIA_TIEN) AS doanh_thu
                 FROM su_kien SK
                 LEFT JOIN thanh_toan TT ON SK.MA_SK = TT.MA_SK
                 WHERE 1=1 $whereClause
                 GROUP BY YEAR(SK.NGAY_BD), MONTH(SK.NGAY_BD)
                 ORDER BY nam DESC, thang DESC";

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy danh sách năm có sự kiện
    public function getAvailableYears() {
        $query = "SELECT DISTINCT YEAR(NGAY_BD) AS nam FROM su_kien ORDER BY nam DESC";
        $result = $this->conn->query($query);
        $years = [];
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['nam'];
        }
        return $years;
    }
}
?>