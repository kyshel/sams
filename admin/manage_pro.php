<?php
require_once("header.php");
?>



<?php


$table_name = "project";
$sql="SELECT * 
from project order by pro_id asc";
$primary_key = 'pro_id';
showGrid($table_name,$sql,$primary_key);












?>