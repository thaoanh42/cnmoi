
<form action="" method="post" enctype= 'multipart/form-data'>
<input type="text" name="cauhoi">
<input type="submit" name="gui">

</form>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("class/connect.php");
$p=new connect();
if(isset($_REQUEST["gui"])){
    $ch=$_REQUEST['cauhoi'];
    $traloi=$p->truyvandulieu("select traloi from chatbot where cauhoi ='$ch' limit 1");
    while($r=$traloi->fetch_assoc()){
        echo $r['traloi'];
    }
}
?>