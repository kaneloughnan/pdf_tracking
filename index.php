<?php
session_start();

if(!isset($_SESSION['userId']))
{
    header("Location: signin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
	<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no"/>
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="img/favicon.png">

	<title>PDF Tracking and Agreement</title>

	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/response.bootstrap.min.css">
	<link rel="stylesheet" href="css/app.css"> 

	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/dataTables.responsive.min.js"></script>
	<script src="js/responsive.boostrap.min.js"></script>
	<script src="js/jquery.validate.js"></script>
        <script src="js/user.js"></script>
    </head>
    <body>
	<div id="header">
	    <div class="btn-group">
		<?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
		    <a href="admin.php"><button class="btn btn-primary btn-sm">Admin</button></a>
		<?php endif; ?>
		<a href="utility/logout.php"><button class="btn btn-primary btn-sm">Logout</button></a>
	    </div>
	    <img src="img/placeholder.png">
	</div>
	
	<div class="content">
	    <div class="row">
		<div class="col-md-6">
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">User details</h4>
			</div>
			<div class="panel-body">			    
			    <table id="userDetails" class="table table-bordered table-hover table-striped">
				<tbody></tbody>
			    </table>
			    
			    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editPasswordModal">Change password</button>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		</div><!--End of col-->
		<div class="col-md-6">
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Assigned modules</h4>
			</div>
			<div class="panel-body">
			    <table id="assignedModules" class="table table-bordered table-hover table-striped">
				<thead>
				    <tr>
					<th>Module name</th>
					<th>PDF</th>
					<th>Viewed</th>
					<th></th>
				    </tr>
				</thead>
				<tbody></tbody>
			    </table>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		</div><!--End of col-->
	    </div><!--End of row-->
	</div><!--End of content-->
	
	<!-- Modal -->
	<div class="modal fade" id="editPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <form name="editPassword">
			<div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			    <h5 class="modal-title" id="myModalLabel">Change password</h5>
			</div>
			<div class="modal-body">
			    <div class="row">
				<div class="form-group">
				    <label class="col-md-3 control-label">Password</label>
				    <div class="col-md-9">
					<input type="password" name="password" placeholder="Password" class="form-control">
				    </div>
				</div>
			    </div>
			    <div class="row">
				<div class="form-group">
				    <label class="col-md-3 control-label">Confirm password</label>
				    <div class="col-md-9">
					<input type="password" name="confirmPassword" placeholder="Confirm password" class="form-control">
				    </div>
				</div>
			    </div>
			</div>
			<div class="modal-footer">
			    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			    <button type="submit" class="btn btn-primary">Submit</button>
			</div>
		    </form>
		</div>
	    </div>
	</div>
    </body>
</html>