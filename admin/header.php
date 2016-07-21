<?php
require_once("auth.php");
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CDTF SAMS</title>

<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/bootstrap-theme.min.css">
<script src="js/jquery-3.1.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<link rel="stylesheet" href="css/sams.css">

</head>

<body>
<div>this is header, 
U R <?php echo $_SESSION['user_name']; ?>,
Your Role is <?php echo $_SESSION['user_role']; ?>,
<a href="login.php?logout">logout</a>
</div>

<br/>
<div>
<a href="add.php">add</a>  
</div>


