<?php
require_once("header.php");
$pro_id=isset($_GET['pro_id']) ? $_GET['pro_id'] : die(' pro_id is not set');

//echo '<a class="btn btn-default btn-sm" href="set_course.php"><<返回</a>';
echo '<a href="set_course.php"><<返回</a>';
//echo '&nbsp';;
//echo '<a href="add_main.php?pro_id='.$pro_id.'">点名</a>';
getProDetail($pro_id,$course_id,$year,$term,$stu_grade,$stu_major,$last_update);
$course_name=getCourseName($course_id);
echo '<br><span>您选择的课程为：'.s($year).'学年,'.s($term).'学期,'.s($course_name).'</span>';
?>

<style type="text/css">
	#right_div,#filter_div{
		border-style: solid;
		border-color: #eee;
		width:400px;
	}
	/* td ceiling*/
	td{
		vertical-align:top;
	}
</style>


<table>
	<tr>
		<td>
			<div id="filter_div">

				<form id="filter_form">
					<span>请选择要添加到此课程的学生：</span><br>
				
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

			
			<div id="left_div"><?php makeFormForAddStudent($pro_id); ?></div>
			</div>		
		</td>
		<td>
			<div id="right_div"><?php makeFormForDelStudent($pro_id) ?></div>	
		</td>
	</tr>
</table>






<script type="text/javascript">

function filter(){
	var pro_id = getParameterByName('pro_id');
	var url = "ajax.php?action=filter_stu_for_add&pro_id="+pro_id+"&sid="+Math.random(); 
	$.ajax({
		type: "POST",
		url: url,
		data: $("#filter_form").serialize(), 
		success: function(data)
		{
			$("#left_div").html(data);
		}
	}); 
}


function add(){
	var pro_id = getParameterByName('pro_id');
	var url = "ajax.php?action=add_student_to_course&sid="+Math.random(); 
	$.ajax({
		type: "POST",
		url: url,
		data: $("#add_form").serialize(), 
		success: function(data)
		{
			$("#right_div").html(data);
			$.ajax({
				url: "ajax.php?action=refresh_left_div&pro_id="+pro_id+"&sid="+Math.random(),
				success: function(data){
					$("#left_div").html(data);
					$('#filter_button').click();
				}
			});
		}
	});
}


function del(){

	var confirm_del=confirm('删除选中的学生将同时删除学生对应的旷课记录！\n您确定要删除？');
	if (confirm_del == false) {
		return;
	}



	var pro_id = getParameterByName('pro_id');
	var url = "ajax.php?action=del_student_from_course"; 
	$.ajax({
		type: "POST",
		url: url,
		data: $("#del_form").serialize(), 
		success: function(data)
		{
			$("#right_div").html(data);
			$.ajax({
				url: "ajax.php?action=refresh_left_div&pro_id="+pro_id+"&sid="+Math.random(),
				success: function(data){
					$("#left_div").html(data);
					$('#filter_button').click();
				}
			});
		}
	});
}




function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}

//after ajax
$(document).ajaxSuccess(function(){
	$("#add_check_all").change(function () {
		$(".add_check_box").prop('checked', $(this).prop("checked"));
	});
	$("#del_check_all").change(function () {
		$(".del_check_box").prop('checked', $(this).prop("checked"));
	});
	
});

//first load page
$("#add_check_all").change(function () {
    $(".add_check_box").prop('checked', $(this).prop("checked"));
});
$("#del_check_all").change(function () {
    $(".del_check_box").prop('checked', $(this).prop("checked"));
});


</script>
