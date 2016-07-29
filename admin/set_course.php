<?php
require_once("header.php");
?>


<?php

$tea_id=$_SESSION['tea_id'];

showGrid('project',"SELECT pro_id,stu_grade,stu_major,course_id from project where tea_id = '$tea_id' order by pro_id",'pro_id');



























?>