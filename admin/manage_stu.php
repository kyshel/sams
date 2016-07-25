<?php
require_once("header.php");

showGrid('student','SELECT * from student','stu_id');

echo '<a href="'.php_self().'?op=add'.'">add</a>';
