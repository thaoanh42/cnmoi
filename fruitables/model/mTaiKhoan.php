<?php
    include_once("connect.php");
    class mTaiKhoan {
        public function mGetAllTaiKhoan() {
            $db = new clsKetNoi;
            $conn = $db->moketnoi();
            
            if ($conn != NULL) {
                $sql = "SELECT * FROM taikhoan";
            
                return $conn->query($sql);
            } return 0;
        }
    }
?>