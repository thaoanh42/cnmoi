<?php
session_start();
session_unset();  // Hủy tất cả các session
session_destroy();  // Hủy session

// Cách an toàn nhất để chuyển hướng đúng
header("Location: /cnmoi/fruitables/index.php");
exit();
?>
