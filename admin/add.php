<?php
require_once("header.php");
?>

<form action="add_main.php" method="post">
<span>Please choose course:</span>
<br>

<?php echoMyCourseSelect($_SESSION['tea_id']);?>

<input type="submit" name="add_submit">
</form>






<script type="text/javascript">
var xmlhttp;
function loadXMLDoc(url,cfunc)
{
	if (window.XMLHttpRequest)
	{
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	// if (xmlHttp==null)
	// {
	// 	alert ("Browser does not support HTTP Request")
	// 	return
	// } 
	xmlhttp.onreadystatechange=cfunc;
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}

function showAddedDate(str)
{
	var url="ajax.php?getAddedDate"
	url=url+"&pro_id="+str
	url=url+"&sid="+Math.random()

	loadXMLDoc(url,function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("dp_wrapper").innerHTML=xmlhttp.responseText;
			eval(document.getElementById("dp_run").innerHTML);

			
		}
	});
}

$( document ).ready(function() {
	var str = document.getElementById("course_list").value;
    showAddedDate(str);
}); 

</script>
