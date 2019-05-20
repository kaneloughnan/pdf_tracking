<?php
session_start();

if(isset($_SESSION['userId']))
{
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="shortcut icon" href="img/favicon.png">

	<title>PDF Tracking and Agreement</title>

	<link rel="stylesheet" href="css/signin.css">
	
	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/jquery.validate.js"></script>
	<script src="js/signin.js"></script>
    </head>
    <body>
	<div class="overlay"></div>
	
	<div class="content">
	    <div class="header">
		<img src="img/placeholder.png">
	    </div>
	    
	    <div id="loginMessage"></div>

	    <form name="login">
		<h3>Login to view modules</h3>
		
		<div class="inputContainer">
		    <input type="text" name="username" placeholder="Username">
		</div>
		<div class="inputContainer">
		    <input type="password" name="password" placeholder="Password">
		</div>
		
		<div>
		    <input type="submit" value="Submit">
		</div>
		
		<h6>PDF Tracking</h6>
	    </form>
	</div>
    </body>
</html>