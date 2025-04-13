<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    require_once __DIR__."/../models/thanhtoanModel.php";

    class ThanhToanController {
        public function thongtinTT() {
            if (!isset($_SESSION['current_sk'])) {
                header("Location: /Quan_Ly_Su_Kien/app/views/NguoiDung/user.php");
                exit();
            }
            
            // Lấy thông tin sự kiện từ session
            return $_SESSION['current_sk'];
        }
        
        public function formatCurrency($amount) {
            return number_format($amount, 0, ',', '.') . ' VNĐ';
        }
        
        public function formatDateTime($datetime) {
            $date = new DateTime($datetime);
            return $date->format('d/m/Y H:i');
        }
    }
?>