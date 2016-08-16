<?php
require_once("header.php");
?>





<div class="panel panel-primary"> 
	<div class="panel-heading"> 
		<h3 class="panel-title">欢迎使用</h3> 
	</div> 
	<div class="panel-body">
		<p>推荐使用Chrome或Firefox或Safari浏览器</p>
		<p>提示:如果首次使用，请先添加您教授的课程，再添加学生，才可以点名！<br></p>

	</div> 
</div>








<?php

$tip ='
1.请保证专业名的整洁及唯一性，计划表及点名表的生成都依靠专业名
2.请谨慎删除条目，否则删除后记录的无法读取
';

makeHideItem('$tip');


































?>

<?php
//require_once("footer.php");
?>


