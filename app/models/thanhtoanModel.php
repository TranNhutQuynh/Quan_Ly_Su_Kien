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
    }
?>