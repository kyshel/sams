<?php
require_once("header.php");
?>
<script type="text/javascript">
function getParameterByName(name, url) {
	if (!url) url = window.location.href;
	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec(url);
	if (!results) return null;
	if (!results[2]) return '';
	return decodeURIComponent(results[2].replace(/\+/g, " "));
}
// hide filter_form
$( document ).ready(function() {
    var is_set_op=getParameterByName('op');
    //alert(typeof is_set_op);
    if (typeof is_set_op != 'object' && is_set_op != 'update' && is_set_op != 'insert' && is_set_op != 'del') {
        $("#filter_div").hide();
    }

});

</script>

<div class="panel panel-default" id="filter_div"> 
	<div class="panel-heading"> 
		<h3 class="panel-title">设置过滤条件</h3> 
	</div> 
	<div class="panel-body">
		<form id="filter_form">
			<div class="col-md-10">
			<?php 
			makeSelect('condition[stu_dep]','SELECT DISTINCT stu_dep from student ','no_selected',1,'不分学院','all','onchange="filter()"');
			makeSelect('condition[stu_major]','SELECT DISTINCT stu_major from student ','no_selected',1,'不分专业','all','onchange="filter()"');
			makeSelect('condition[stu_grade]','SELECT DISTINCT stu_grade from student ','no_selected',1,'不分年级','all','onchange="filter()"');
			?>
			<input type="number" class="form-control " min='0' name="condition[stu_id]" onkeyup="filter()" placeholder="输入学号过滤">
			</div>
			<div class="col-md-2 kiss_bottom">
				<button type="button" id="filter_button" onclick="filter()" class="btn">过滤</button> 
			</div>
			
		</form>

	</div> 
</div>



<?php
// first load, show all student
echo '<div id="stu_div">';
echo '
<div class="panel panel-default">
	<div class="panel-heading"> 
		<h3 class="panel-title">全部学生</h3> 
		<a href="'.php_self().'?op=add'.'" class="panel-title pull-right" >'.lang('add_new_stu').'</a>
	</div> 
	
	';

showGrid('student','SELECT * from student','stu_id',0,0,1);
echo '</div></div>';
?>

<script type="text/javascript">
// this part won't interpret when url has parameter, cause forwrad php code has die() 
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


