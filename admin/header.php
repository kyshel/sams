<?php
require_once("auth.php");
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CDTF SAMS</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<link rel="stylesheet" href="css/sams.css">

<?php dynamic_css_js_lib(); ?>

</head>

<body>
<div>this is header, 
U R <?php echo $_SESSION['user_name']; ?>,
Your Role is <?php echo $_SESSION['user_role']; ?>,
<a href="login.php?logout">logout</a>
</div>

<div>
<?php showMenuAccordUserRole(); ?>
</div>

<?php //noise('dev mode is open!'); ?>


<hr>


