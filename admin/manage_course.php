<?php
require_once("header.php");
?>

<p>This is <?php echo php_self();?></p>

<?php


$table_name = "project";
$sql="SELECT * 
from project";
$primary_key = 'pro_id';
showGrid($table_name,$sql,$primary_key);












?>