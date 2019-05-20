<?php
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 0)
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
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
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
	<script src="js/jquery.validate-additional-methods.js"></script>
	<script src="js/admin.js"></script>
    </head>
    <body>
	<div id="header">
	    <div class="btn-group">
		<a href="index.php"><button class="btn btn-primary btn-sm">Home</button></a>
		<a href="utility/logout.php"><button class="btn btn-primary btn-sm">Logout</button></a>
	    </div>
	    <img src="img/placeholder.png">
	</div>
	
	<div class="content">
	    <div class="row">
		<div class="col-md-6">
		    <div class="panel panel-default" id="addUserPanel">
			<div class="panel-heading">
			    <h4 class="panel-title">Add user</h4>
			</div>
			<div class="panel-body">
			    <div id="addUserSuccess">
				New user added
			    </div>
			    <form name="addUser">
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Username</label>
					<div class="col-md-10">
					    <input type="text" name="username" placeholder="Username" class="form-control">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Password</label>
					<div class="col-md-10">
					    <input type="password" name="password" placeholder="Password" class="form-control">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Confirm password</label>
					<div class="col-md-10">
					    <input type="password" name="confirmPassword" placeholder="Confirm password" class="form-control">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">First name</label>
					<div class="col-md-10">
					    <input type="text" name="fname" placeholder="First name" class="form-control">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Last name</label>
					<div class="col-md-10">
					    <input type="text" name="lname" placeholder="Last name" class="form-control">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Email</label>
					<div class="col-md-10">
					    <input type="text" name="email" placeholder="Email" class="form-control">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Assign modules</label>
					<div class="col-md-10">
					    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignModulesNewModal" style="width:auto; margin-bottom:10px;">Assign modules</button>
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Admin</label>
					<div class="col-md-10">
					    <input type="checkbox" name="admin" style="display:inline; width:auto; margin-top:6px;">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-2 control-label">Submit</label>
					<div class="col-md-10">
					    <button type="submit" class="btn btn-primary" data-loading-text="Loading..." style="width:auto;">Submit</button>
					</div>
				    </div>
				</div>
			    </form>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		    		    <div class="panel panel-default" id="addModulePanel">
			<div class="panel-heading">
			    <h4 class="panel-title">Add module</h4>
			</div>
			<div class="panel-body">
			    <div id="addModuleSuccess">
				New module added
			    </div>
			    <form name="addModule" enctype="multipart/form-data">
				<div class="row">
				    <div class="form-group">
					<label class="col-md-1 control-label">Name</label>
					<div class="col-md-11">
					    <input type="text" name="name" placeholder="Name" class="form-control">
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-1 control-label">PDF</label>
					<div class="col-md-11">
					    <input type="file" name="pdf" id="pdf" class="pdf-group" accept="application/pdf">
					</div>
				    </div>
				</div>
				<h4 style="text-align:center;">OR</h4>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-1 control-label">Text</label>
					<div class="col-md-11">
					    <textarea name="text" id="text" placeholder="Text" class="form-control pdf-group" style="height:140px;"></textarea>
					</div>
				    </div>
				</div>
				<h4 style="text-align:center;">Add a quiz (optional)</h4>

				<div class="panel-group" id="accordion"></div>

				<div class="row">
				    <div class="form-group">
					<label class="col-md-1 control-label">Add question</label>
					<div class="col-md-11">
					    <button id="addQuestion" class="btn btn-primary" data-loading-text="Loading..." style="width:auto;margin-bottom:10px;">Add question</button>
					</div>
				    </div>
				</div>
				<div class="row">
				    <div class="form-group">
					<label class="col-md-1 control-label">Submit</label>
					<div class="col-md-11">
					    <button id="submitModule" type="submit" class="btn btn-primary" data-loading-text="Loading..." style="width:auto;">Submit</button>
					</div>
				    </div>
				</div>
			    </form>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		</div><!--End of col-->

		<div class="col-md-6">
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">All modules</h4>
			</div>
			<div class="panel-body">
			    <table id="modulesReport" class="table table-bordered table-hover table-striped">
				<thead>
				<tr>
				    <th>Module name</th>
				    <th>PDF</th>
				    <th>Quiz</th>
				    <th></th>
				</tr>
				</thead>
				<tbody></tbody>
			    </table>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">All users</h4>
			</div>
			<div class="panel-body">
			    <table id="users" class="table table-bordered table-hover table-striped">
				<thead>
				<tr>
				    <th>Name</th>
				    <th></th>
				</tr>
				</thead>
				<tbody></tbody>
			    </table>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		</div><!--End of col-->
	    </div><!--End of row-->
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="assignModulesNewModal" class="assignModulesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h5 class="modal-title" id="myModalLabel">Assign modules</h5>
		    </div>
		    <div class="modal-body">
			<label for="assignAllNewModules">Select all &nbsp;<input type="checkbox" id="assignAllNewModules" class="assignAllModules"></label>
			<table class="modulesAssignTable table table-bordered table-hover table-striped">
			    <thead>
				<tr>
				    <th>Module name</th>
				    <th>PDF</th>
				    <th>Quiz</th>
				    <th>Assign</th>
				</tr>
			    </thead>
			    <tbody></tbody>
			</table>
		    </div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
		    </div>
		</div>
	    </div>
	</div>
	
	<!-- Modal -->
	<div class="modal fade" id="assignModulesExistingModal" class="assignModulesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	    <div class="modal-dialog" role="document">
		<div class="modal-content">
		    <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h5 class="modal-title" id="myModalLabel">Assign modules</h5>
		    </div>
		    <div class="modal-body">
			<label for="assignAllExistingModules">Select all &nbsp;<input type="checkbox" id="assignAllExistingModules" class="assignAllModules"></label>
			<table class="modulesAssignTable table table-bordered table-hover table-striped">
			    <thead>
				<tr>
				    <th>Module name</th>
				    <th>PDF</th>
				    <th>Quiz</th>
				    <th>Assign</th>
				</tr>
			    </thead>
			    <tbody></tbody>
			</table>
		    </div>
		    <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="assignModulesBtn" data-loading-text="Loading...">Done</button>
		    </div>
		</div>
	    </div>
	</div>
    </body>
</html>