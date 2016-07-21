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

<!-- <nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
  
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">CDTF SAMS</a>
    </div>

    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav navbar-right">


        <li><a href="">Logout</a></li>
    </div>

  </div>
</nav> -->

