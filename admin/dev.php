<?php
require_once("header.php");

dev_var_dump('get');

echo "show go list:";
showGrid('go','SELECT * from go','go_id');

echoSelectList('stu_grade','student');
echoSelectList('stu_major','student');
echoSelectList('course_code','course');


echo '<input type="submit" value="save">';
?>




<?php

getPrimaryKeyName('go');




?>



