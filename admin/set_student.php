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


<div style="clear: both;"></div>
<div id="select">
<?php makeTableForAddStudent($pro_id); ?>
</div>
<div id="added">
<?php makeTableForAddedStudent($pro_id) ?>
</div>




<script type="text/javascript">

function add(str){
	var pro_id = getParameterByName('pro_id');

	var req="ajax.php?op=add&stu_id="+str;
	req+="&pro_id="+pro_id;
	req+="&sid="+Math.random();

	$.ajax({
		url: req,
		success: function(result){
			$("#added").html(result);
			$.ajax({
				url: "ajax.php?op=add_refresh&pro_id="+pro_id+"&sid="+Math.random(),
				success: function(result){
					$("#select").html(result);
				}
			});
		}
	});

	

}



function del(str){
	var confirm_del=confirm('删除此学生将同时删除此学生的旷课记录！\n您确定要删除？');
	if (confirm_del == false) {
		return;
	}
	
	var pro_id = getParameterByName('pro_id');

	var req="ajax.php?op=del&stu_id="+str;
	req+="&pro_id="+pro_id;
	req+="&sid="+Math.random();

	$.ajax({
		url: req,
		success: function(result){
			$("#added").html(result);
			$.ajax({
				url: "ajax.php?op=add_refresh&pro_id="+pro_id+"&sid="+Math.random(),
				success: function(result){
					$("#select").html(result);
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
</script>
<style type="text/css">
	#select{
		border-style: solid;
		border-color: #eee;
		width:400px;
		

		float: left;
	}
	#added{
		border-style: solid;
		border-color: #eee;
		width:400px;
		

		float: left;
	}
</style>