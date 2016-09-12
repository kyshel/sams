<?php
require_once("header.php");
?>





<div class="panel panel-primary"> 
	<div class="panel-heading"> 
		<h3 class="panel-title">欢迎使用</h3> 
	</div> 
	<div class="panel-body">
		<p>请使用Chrome或Firefox或Safari浏览器！</p>
		<p>提示:如果首次使用，请先添加您教授的课程，再添加学生，才可以点名！<br></p>

	</div> 
</div>



<div class="panel panel-primary"> 
	<div class="panel-heading"> 
		<h3 class="panel-title">开发日志</h3> 
	</div> 
	<div class="panel-body" >
	<p>
		

		160911 增加班级过滤<br><br>

		160819 增加个人分析<br>
		160818 完成字段验证<br>
		160817 设置学年自动计算，页面功能表述已改<br>
		160816 设计登录界面，设计后台界面，扩大字段空隙，添加挪到右上角<br>
		160815 增加查看旷课人数、次数功能，增加旷课次数达标提示<br>
		160814 增加按学号、旷课次数，排序功能，增加旷课次数最大值约束<br>
		160813 增加结课功能，添加课程后自动添加对应学生功能<br>
		160812 增加过滤功能，增加学号检索<br>
		160811 增加全选功能，增加课时数<br><br>

		160810 系统第二次报告测试<br>
		160809 重建添加课程功能，增加删除提示<br>
		160808 完成添加学生功能，但效率较低 <br>
		160807 重建数据库，删除go表<br><br>

		160729 系统第一次报告测试<br>
		160728 完成查看功能<br>
		160727 完成已点名日期高亮显示功能<br>
		160726 增加ajax<br>
		160725 完成学生、老师、课程管理功能<br>
		160724 增加开发调试功能<br>
		160723 完成录入功能<br>
		160722 完成超时踢出功能<br>
		160721 完成登录功能<br>
		160720 建立jquery,bootstrap包含文件<br>
		160719 建立目录结构，搭建VMVare+LAMP+SAMBA+SublimeText+Chrome开发环境<br>
		160718 建立数据库结构，学生，教师，课程，pro，go，attend<br>
	</p>


	</div> 
</div>



<!-- <div class="panel panel-primary"> 
	<div class="panel-heading"> 
		<h3 class="panel-title">测试中</h3> 
	</div> 
	<div class="panel-body" >
	<p>
	导入导出，找回密码，结果展示
	</p>


	</div> 
</div> -->






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


