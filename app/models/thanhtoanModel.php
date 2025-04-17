<?php
    require_once __DIR__."/../database.php";

    class thanhtoanModel{
        private $conn;

        public function __construct(){
            global $conn;
            $this->conn = $conn;
        }

        public function getSuKien($maSK){
            $query = "SELECT *FROM su_kien WHERE MA_SK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $maSK);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        public function getThanhToanInfo($maSK) {
            $query = "SELECT * FROM thanh_toan WHERE MA_SK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $maSK);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        
        public function createThanhToan($maSK, $tenKH, $tongTien, $trangThai = 'Chưa thanh toán', $phuongThuc = NULL) {
            $ngayThanhToan = NULL;
            if ($trangThai == 'Đã thanh toán') {
                $ngayThanhToan = date('Y-m-d');
            }
            
            $query = "INSERT INTO thanh_toan (MA_SK, KHACH_HANG, TONG_TIEN_TT, TRANG_THAI, PHUONG_THUC, NGAY_THANH_TOAN) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("isdsss", $maSK, $tenKH, $tongTien, $trangThai, $phuongThuc, $ngayThanhToan);
            return $stmt->execute();
        }
        
        public function updateThanhToan($maSK, $trangThai, $phuongThuc = NULL) {
            $ngayThanhToan = NULL;
            if ($trangThai == 'Đã thanh toán') {
                $ngayThanhToan = date('Y-m-d');
            }
            
            $query = "UPDATE thanh_toan SET TRANG_THAI = ?, PHUONG_THUC = ?, NGAY_THANH_TOAN = ? WHERE MA_SK = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $trangThai, $phuongThuc, $ngayThanhToan, $maSK);
            return $stmt->execute();
        }
        
        public function getAllThanhToan() {
            $query = "SELECT tt.*, sk.TEN_SK, sk.LOAI_SK, sk.TEN_KH, sk.NGUOI_THAM_GIA 
                      FROM su_kien sk
                      LEFT JOIN thanh_toan tt ON tt.MA_SK = sk.MA_SK
                      ORDER BY sk.NGAY_BD DESC";
            $result = $this->conn->query($query);
            
            $thanhToanList = [];
            while ($row = $result->fetch_assoc()) {
                $thanhToanList[] = $row;
            }
            return $thanhToanList;
        }
        
        // Thêm phương thức để cập nhật hoặc tạo thanh toán với giá tiền
        public function saveThanhToan($maSK, $tenKH, $tongTien, $trangThai = 'Chưa thanh toán', $phuongThuc = NULL) {
            // Kiểm tra xem đã có bản ghi thanh toán chưa
            $existingRecord = $this->getThanhToanInfo($maSK);
            
            if ($existingRecord) {
                // Nếu đã có bản ghi, cập nhật trạng thái nhưng giữ nguyên số tiền
                return $this->updateThanhToan($maSK, $trangThai, $phuongThuc);
            } else {
                // Nếu chưa có, tạo mới bản ghi với giá tiền
                return $this->createThanhToan($maSK, $tenKH, $tongTien, $trangThai, $phuongThuc);
            }
        }
    }
?>