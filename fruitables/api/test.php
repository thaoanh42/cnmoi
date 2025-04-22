<?php
include("../class/connect.php");

$p = new connect();
$p->xuatsinhvien("select * from chatbot order by id");
?>