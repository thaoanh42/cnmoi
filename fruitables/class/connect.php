<?php
class connect{
    
    public function connectDB(){
        
        $con = mysqli_connect("localhost", "root", "", "ecofarm");
		if(!$con){
			echo "Ket noi khong thanh cong!";
			exit();	
		}	
		else{
			//mysql_select_db("cnmoi");
			mysqli_query($con, "SET NAMES UTF8");
			return $con;
		}
    }
    public function xuatsinhvien($sql){
		$link = $this->connectDB();
		$result = mysqli_query($link, $sql);
		$i = mysqli_num_rows($result);
		if($i > 0){
			$dulieu = array();
			while($row = mysqli_fetch_array($result)){
				$id = $row['id'];	
				$cauhoi = $row['cauhoi'];
				$traloi = $row['traloi'];
				$dulieu[]=array('id'=>$id, 'cauhoi'=>$cauhoi, 'traloi'=>$traloi);
			}
			
			header("content-Type:application/json; charset=UTF-8");			
			echo json_encode($dulieu);
		}		
	}
    
    public function chatbot($sp){
        $con = $this -> connectDB();
        
        if($con){
            $str = "select traloi from chatbot where cauhoi like N'%$sp%' limit 1";
            
            $tblSP =mysqli_query($con, $str);
            return $tblSP; 
        }else{
            return false; // khong the ket noi CSDL
        }
    }

    public function truyvandulieu($sql){
		$link = $this->connectDB();
		if(mysqli_query($link, $sql)){
			return mysqli_query($link, $sql);
		}
		else
		{
			return 0;
		}
	}

    

}
?>