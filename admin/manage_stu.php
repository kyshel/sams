<?php
require_once("header.php");
?>

<form id="filter_form">
	<!-- <span>请选择要添加到此课程的学生：</span><br> -->

	<span>条件过滤：</span>
	<?php 
	makeSelect('condition[stu_dep]','SELECT DISTINCT stu_dep from student ','no_selected',1,'不分学院','all','onchange="filter()"');
	makeSelect('condition[stu_major]','SELECT DISTINCT stu_major from student ','no_selected',1,'不分专业','all','onchange="filter()"');
	makeSelect('condition[stu_grade]','SELECT DISTINCT stu_grade from student ','no_selected',1,'不分年级','all','onchange="filter()"');
	?>
	<br><span>学号过滤：</span>
	<input type="number" min='0' name="condition[stu_id]" onkeyup="filter()" placeholder="输入学号过滤">
	<button type="button" id="filter_button" onclick="filter()" >过滤</button> 
</form>

<?php
// first load, show all student
echo '<div id="stu_div">';
showGrid('student','SELECT * from student','stu_id');
echo '</div>';
?>

<script type="text/javascript">

function filter(){
	var url = "ajax.php?action=filter_stu_for_manage&sid="+Math.random(); 
	$.ajax({
		type: "POST",
		url: url,
		data: $("#filter_form").serialize(), 
		success: function(data)
		{
			$("#stu_div").html(data);
		}
	}); 
}

</script>


