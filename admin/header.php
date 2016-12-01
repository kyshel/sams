<?php
require_once("auth.php");
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CDTF SAMS</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="css/bootstrap-theme.min.css"> -->

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/sams.css">
<link rel="stylesheet" href="css/dashboard.css">

<script src="js/validator.min.js"></script>
<script src="js/tablesort.min.js"></script>
<script src="js/tablesort-number.js"></script>
<?php echoRequireLib(); ?>

<script type="text/javascript">
	$(document).ready(function() { 
		if (document.getElementById("tablesort")) {
			new Tablesort(document.getElementById('tablesort'), {
			});
			document.getElementById('tablesort').addEventListener('afterSort', function() {
				$('.danger').tooltip('show');
			});
		}

		//$('.noise_div').collapse('hide');
		//$('.dev_dump_div').collapse('hide');
		
		$('.noise_div').collapse('show');
		$('.dev_dump_div').collapse('show');
	});
</script>

</head>
<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar_collapse" aria-expanded="false" aria-controls="sidebar_collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php">CDTF考勤录入系统</a>
		</div>

		<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav navbar-right" >
				<!-- <li><a href="#"><?php echo $_SESSION['user_role']; ?></a></li> -->
				<li><a href="#"><?php echo $_SESSION['user_name']; ?></a></li>
				<li><a href="login.php?logout">退出</a></li>
			</ul>

		</div>
	</div>
</nav>

<div class="sidebar-collapse collapse navbar-inverse" id="sidebar_collapse" >
	<ul class="nav navbar-nav "  >
		<?php showMenuAccordUser(); ?>
		<!-- <li><a href="#"><?php echo $_SESSION['user_role']; ?></a></li> -->
		<li><a href="#"><?php echo $_SESSION['user_name']; ?></a></li>
		<li><a href="login.php?logout">退出</a></li>		
	</ul>
</div>

<div class="container-fluid">
	<div class="row">

		<div class="col-sm-3 col-md-2 sidebar">
			<ul class="nav nav-sidebar "  >
				<?php showMenuAccordUser(); ?>
				<li><a><?php (DEV_MODE == 1)?echoRed('Debugging!'):'';?></a></li>				
			</ul>
		</div>

		


		<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"> 
		










<!-- 		</div>
	</div>
</div>
 -->

