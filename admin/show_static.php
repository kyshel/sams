<?php
require_once("header.php");
?>

<!-- ****************** calc top menu ************-->
<ul class="nav nav-tabs">
<?php
$php_self=php_self();
$array_menu=array(
	'pro' => '课程分析',
	'class' => '班级分析',
	'stu' => '学生分析',
	);

// default active card
$active_type=isset($_GET['type'])?$_GET['type']:'pro';
foreach ($array_menu as $key => $value) {	
	$is_active=($key == $active_type)?'class="active"':'';
	echo '<li role="presentation" '.$is_active.'><a href="'.$php_self.'?type='.$key.'">'.$value.'</a></li>  ';		
}
?>
</ul>

<!-- ****************** echo form and static ************-->

<?php
isset($_GET['type'])?echoFormForStatic($_GET['type']):echoFormForStatic('pro');
isset($_POST['type'])?echoStaticByPost($_POST['type']):isset($_GET['static'])?echoStaticByGet($_GET['type']):'';
?>

<!-- ****************** canvas data make ************ -->
<script type="text/javascript">	
var data=<?php
$type=isset($_POST['type'])?$_POST['type']:(isset($_GET['static'])?$_GET['type']:'');
if (!empty($type)) {
	switch ($type) {
	

	case 'pro':
		$cut=1-$cacel_exam_ratio;
		// $array_data = array(
		// 	'取消考试资格' => $cacel_exam_ratio ,
		// 	'' => $cut ,
		//  );

		$array_data = array($cacel_exam_ratio,$cut);
		
		echo json_encode($array_data, JSON_UNESCAPED_UNICODE); 

		break;

	case 'class':

		break;

	case 'stu':

		break;
	
	default:
		
		break;
}
}

?>
;
$(document).ready(function() { 
	paint(data);
});

</script>



<!-- ************ js funcs ************ -->
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

function paint(data) {
	//var data = [1, 1, 2, 3, 5, 8, 13];

	var canvas = document.getElementById("cancel_exam_pie"),
	context = canvas.getContext("2d");

	var width = canvas.width,
	height = canvas.height,
	radius = Math.min(width*0.9, height*0.9) / 2;

	// var colors = function(){
	// 	return "hsl(" + Math.random() * 360 + ",100%,50%)";
	// } 

	var colors=['#ff3b54','#00b4d9'];

	var arc = d3.arc()
	.outerRadius(radius - 10)
	.innerRadius(0)
	.context(context);

	var labelArc = d3.arc()
	.outerRadius(radius - 0)
	.innerRadius(radius - 0)
	.context(context);



	var pie = d3.pie();
	var arcs = pie(data);

	// move to center
	context.translate(width / 2, height / 2);


	// pie
	context.globalAlpha = 1;
	arcs.forEach(function(d, i) {
		context.beginPath();
		arc(d);
		//context.fillStyle = colors();
		context.fillStyle = colors[i];
		context.fill();
	});

	// num
	context.textAlign = "center";
	context.textBaseline = "middle";
	context.fillStyle = "#000";
	arcs.forEach(function(d, i) {
		var c = labelArc.centroid(d);
		if (i==0) {
			var ratio=data[i]*100;
			var mes='取消考试：'+ratio.toFixed(1)+'%';
			context.fillText(mes, c[0], c[1]);
		}
		
	});

	//outer line
	// context.globalAlpha = 1;
	// context.beginPath();
	// arcs.forEach(arc);
	// context.lineWidth = 1.5;
	// context.stroke();
	}







</script>



