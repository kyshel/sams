<?php
require_once("auth.php"); // include functions.php

$json_static = getStaticJson();
//echo getPre($json_static);

// 161130
// ensure result.json is writeable
// why make json to a file ?
// just keep json readable,touchable,persistent
// result.json can be seen a middle ware
// it connect php and javascript
$fp = fopen('result.json', 'w');
fwrite($fp, $json_static);
fclose($fp);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="json2table">
	<meta name="author" content="kyshel">
	<title>SAMS - Details</title>

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/viewer.css" rel="stylesheet">
	<style type="text/css">
		#te{
			/*width: 700px;*/
			margin: 70px auto;
		}
	</style>

</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">SAMS - Details</a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

				<ul class="nav navbar-nav navbar-right">
				<form class="navbar-form navbar-left">
					<button class="btn btn-info" onclick="json2xls()"><i class="glyphicon glyphicon-export icon-share"></i> 导出</button>
				</form>					

				</ul>		

			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>


	<!-- Begin page content -->
	<div id="table_pnl">
		<div id="inner_tbl"></div>
	</div>



	<!-- Json verify -->
	<div class="modal fade bs-example-modal-sm" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Invalid JSON!</h4>
				</div>
				<div class="modal-body">
					<strong>Error: </strong><span id="error_msg"></span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
				</div>
			</div>
		</div>
	</div>





	<script src="js/jquery.min.js"></script>  
	<script src="js/bootstrap.min.js"></script>
	<script src="js/viewer.js"></script>
	<script src="js/jquery.base64.js"></script>

	<script type="text/javascript">

		$.getJSON("result.json", function(data) {
			console.log(data); 
			$("#inner_tbl").html(buildTable(data));
		});

	</script>






	<script type="text/javascript">

		function json2xls(){
			var defaults = {
				type:'excel'
			};

			excel = $("#inner_tbl").html();

			var excelFile = "<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:x='urn:schemas-microsoft-com:office:"+defaults.type+"' xmlns='http://www.w3.org/TR/REC-html40'>";
			excelFile += "<head>";
			excelFile += "<!--[if gte mso 9]>";
			excelFile += "<xml>";
			excelFile += "<x:ExcelWorkbook>";
			excelFile += "<x:ExcelWorksheets>";
			excelFile += "<x:ExcelWorksheet>";
			excelFile += "<x:Name>";
			excelFile += "{worksheet}";
			excelFile += "</x:Name>";
			excelFile += "<x:WorksheetOptions>";
			excelFile += "<x:DisplayGridlines/>";
			excelFile += "</x:WorksheetOptions>";
			excelFile += "</x:ExcelWorksheet>";
			excelFile += "</x:ExcelWorksheets>";
			excelFile += "</x:ExcelWorkbook>";
			excelFile += "</xml>";
			excelFile += "<![endif]-->";
			excelFile += "</head>";
			excelFile += "<body>";
			excelFile += excel;
			excelFile += "</body>";
			excelFile += "</html>";

			var base64data = "base64," + $.base64.encode(excelFile);
			window.open('data:application/vnd.ms-'+defaults.type+';filename=exportData.xls;' + base64data);
		}


	</script>

</div><!--main end-->
</body>
</html>

