<?php
require_once("header.php");
echo '
<div class="panel panel-default">
	<div class="panel-heading"> 
		<h3 class="panel-title">课程</h3> 
		<a href="'.php_self().'?op=add'.'" class="panel-title pull-right" >'.lang('add').'</a>
	</div> 
	
	';
showGrid('course','SELECT * from course','course_id',0,0,1);
echo '</div></div>';