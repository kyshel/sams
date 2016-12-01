<?php
require_once("header.php"); // include functions.php
require_once("Statistic.php");
?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">结果统计</h3>
	</div>
	<div class="panel-body">
		<?php
		echo '截止到';
		echo getNowTime();
		echo '';
		echo ' , 共有'.getProjectCount().'门考勤课程';

		?>


		<br><br>
		<a href="pretty_result.php" target="_blank"><button class="btn btn-primary">查看详细结果</button></a>
		
	</div>
</div>