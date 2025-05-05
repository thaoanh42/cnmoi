<?php
    include_once("../../model/mTaiKhoan.php");
    
    class cTaiKhoan extends mTaiKhoan {
        public function cGetAllTaiKhoan () {
            if ($this->mGetAllTaiKhoan() != 0)
                return $this->mGetAllTaiKhoan();
            return 0;
        }
    }
?>