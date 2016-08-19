<?php
require_once("header.php");
?>
<a href="<?php echo php_self();?>?type=pro">课程分析</a>
<a href="<?php echo php_self();?>?type=class">班级分析</a>
<a href="<?php echo php_self();?>?type=stu">学生分析</a>
<?php
isset($_GET['type'])?echoFormForStatic($_GET['type']):echoFormForStatic('class');
isset($_POST['type'])?echoStaticByPost($_POST['type']):isset($_GET['static'])?echoStaticByGet($_GET['type']):'';

?>






<script type="text/javascript">	

function show_static(type){

	var build_form="#form_"+type;
	var url = "ajax.php?action=show_static&type="+type+"&sid="+Math.random(); 
	$.ajax({
		type: "POST",
		url: url,
		data: $(build_form).serialize(), 
		success: function(data)
		{
			$("#div_data").html(data);

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









	$(document).ready(function() { 


		document.getElementById('tablesort').addEventListener('afterSort', function() {
			$('.danger').tooltip('show');
		});

		$('.danger').tooltip('show');

	});
</script>



<?php
// temp code
die();
?>

<?php $type='class';?>
<form id="form_<?php echo $type;?>" method="post">
<span>选择班级:</span>
<br>
<?php echoClassSelect();?>
<button type="button" onclick="show_static('<?php echo $type;?>')">提交</button>
</form>
<hr>

<?php $type='stu';?>
<form id="form_<?php echo $type;?>" method="post">
<span>输入学号:</span>
<br>
<input type="number" name="stu_id" >
<button type="button" onclick="show_static('<?php echo $type;?>')">提交</button>
</form>
<hr>