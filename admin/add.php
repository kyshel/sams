<?php
require_once("header.php");
?>

<?php
echo 'this is test zone>>>>>>>>>>>>>>>>>>>>>> <br/>';
echo $_SESSION['tea_id'];
echo '<br/> this is test zone<<<<<<<<<<<<<<<<<<<<<<';
?>


<p>This is add.php</p>

<p>Please choose:</p>
<p>Course</p>
<?php teacher_get_own_course($_SESSION['tea_id']);?>
<p>Date</p>
<p>Major</p>
<p>Grade</p>

