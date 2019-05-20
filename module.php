<?php
session_start();

if(!isset($_SESSION['userId']))
{
    header("Location: signin.php");
    exit();
}

require_once('class/autoloader.php');
require_once('class/Module.php');
require_once('class/Reporting.php');

$moduleClass = new Module();
$moduleId = $_GET['moduleId'];
$module = $moduleClass->getModuleDetails($moduleId);
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
	<link rel="stylesheet" href="css/app.css">
	<link rel="stylesheet" href="css/question.css">

	<script src="js/jquery-2.1.1.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script src="js/question.js"></script>
	<script>
	    var moduleId = <?php echo $moduleId; ?>;
	    var moduleType = 'text';
	</script>
	<script src="js/reporting.js"></script>
    </head>
    <body>
	<input type="button" id="finishBtn" value="I've Read and Understood this" class="btn btn-primary">
    <div class="modal " id="finish-reading-popup">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Quiz</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="nextQuestion" class="btn btn-primary" disabled="disabled">Next question</button>
                    <button type="button" id="finishBtnx2" class="btn btn-primary" disabled="disabled">I've Read and Understood this</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
	<div id="header">
	    <div class="btn-group">
		<?php if(isset($_SESSION['admin']) && $_SESSION['admin'] == 1): ?>
		    <a href="admin.php"><button class="btn btn-primary btn-sm">Admin</button></a>
		<?php endif; ?>
		<a href="utility/logout.php"><button class="btn btn-primary btn-sm">Logout</button></a>
	    </div>
	    <img src="img/ESTE_logo_small.png">
	</div>
	
	<div class="content" style="margin:20px 20px 50px 20px; padding:10px; background-color:#fff;">
	    <?php echo $module->text; ?>
	</div>
    </body>
</html>