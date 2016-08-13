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

$( document ).ready(function() {
    var is_set_op=getParameterByName('op');
    //alert(typeof is_set_op);
    if (typeof is_set_op != 'object' && is_set_op != 'update' && is_set_op != 'insert') {
        $("#filter_form").hide();
    }

});

</script>

<form id="filter_form">
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
showGrid('student','SELECT * from student','stu_id',0,0);
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


