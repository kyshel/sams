<?php
require_once("header.php");
?>


<?php

$tea_id=$_SESSION['tea_id'];

showGrid('project',"SELECT pro_id,course_id,year,term,stu_grade,stu_major from project where tea_id = '$tea_id' order by pro_id",'pro_id');

?>





























