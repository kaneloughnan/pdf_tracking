<?php
session_start();

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 0)
{
    header("Location: signin.php");
    exit();
}

require_once('class/Model.php');
require_once('class/Module.php');
require_once('class/Quiz.php');
require_once('class/Reporting.php');

$moduleClass = new Module();
$quiz = new Quiz();
$reporting = new Reporting();
$moduleId = $_GET['moduleId'];
$module = $moduleClass->getModuleDetails($moduleId);

if(!isset($_GET['moduleId']) || !$module)
{
    header("Location: admin.php");
    exit();
}

$quizQuestions = $quiz->getQuizDetails($moduleId);

//If the selected module has a question
if($module->quiz)
{
    $moduleQuestion = true;
}
else
{
    $moduleQuestion = false;
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
        <script>
            $(function(){
                $('#usersThatHaveViewedModule').DataTable({
                    scrollX: true,
                    bAutoWidth: false,
                    columnDefs: [
                        {targets: [3], orderData:[0]},//Start time - order by startTimestamp
			{targets: [4], orderData:[1]},//End time - order by unixDifference
			{targets: [5], orderData:[2]},//Time taken - order by startTimestamp
                        {targets: [0], visible: false, searchable: false},//startTimestamp
                        {targets: [1], visible: false, searchable: false},//endTimestamp
                        {targets: [2], visible: false, searchable: false}//unixDifference
                    ]
                });
                
                $('#usersThatHaventViewedModule').DataTable({
                    scrollX: true,
                    bAutoWidth: false
                });
            });
        </script>
    </head>
    <body>
	<div id="header">
	    <div class="btn-group">
		<a href="admin.php"><button class="btn btn-primary btn-sm">Admin</button></a>
		<a href="utility/logout.php"><button class="btn btn-primary btn-sm">Logout</button></a>
	    </div>
	    <img src="img/ESTE_logo_small.png">
	</div>
	
	<div class="content">
	    <h3 style="margin-top:0;"><?php echo $module->name; ?></h3>
	    
	    <div class="row">
		<div class="col-md-6">
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Users that have read the module</h4>
			</div>
			<div class="panel-body">
			    <table id="usersThatHaveViewedModule" class="table table-bordered table-hover table-striped">
				<thead>
				    <tr>
					<th>startTimestamp</th>
                                        <th>endTimestamp</th>
                                        <th>unixDifference</th>
					<th>Name</th>
					<th>Start time</th>
					<th>End time</th>
                                        <th>Time taken</th>
					<?php
					for($i = 1; $i <= count($quizQuestions); $i++)
					{
					?>
					    <th>Q<?php echo $i; ?> attempts</th>
					<?php
					}
					?>
				    </tr>
				</thead>
				<tbody>
				    <?php
				    foreach($reporting->getUsersThatHaveViewedModule($moduleId) as $user)
				    {
                                        $timeTaken = $reporting->timeTaken($user->timeTaken);
//					
//					//If there is a question for this module
//					if($moduleQuestion)
//					{
//					    //If there have been attempts made
//					    if($user->questionAttempts)
//					    {
//						$questionAttempts = $user->questionAttempts;
//					    }
//					    else
//					    {
//						$questionAttempts = "Not attempted";
//					    }
//					}
//					else
//					{
//					    $questionAttempts = 'N/A';
//					}
				    ?>
					<tr>
					    <td><?php echo $user->start; ?></td>
					    <td><?php echo $user->end; ?></td>
                                            <td><?php echo $timeTaken->unix; ?></td>
					    <td><?php echo $user->fname . " " . $user->lname; ?></td>
                                            <td><?php echo $reporting->reportingTime($user->start); ?></td>
					    <td><?php echo $reporting->reportingTime($user->end); ?></td>
                                            <td><?php echo $timeTaken->text; ?></td>
					    <?php
					    foreach($quizQuestions as $quizQuestion)
					    {
						$quizAttempt = $quiz->getQuizAttempt($quizQuestion->quizId, $user->userId);
						
						if($quizAttempt)
						{
						    $attempt = $quizAttempt->attempts;
						}
						else
						{
						    $attempt = "N/A";
						}
					    ?>
						<td><?php echo $attempt; ?></td>
					    <?php
					    }
					    ?>
					</tr>
				    <?php
				    }
				    ?>
				</tbody>
			    </table>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		</div><!--End of col-->
		<div class="col-md-6">
		    <div class="panel panel-default">
			<div class="panel-heading">
			    <h4 class="panel-title">Users that haven't read the module</h4>
			</div>
			<div class="panel-body">
			    <table id="usersThatHaventViewedModule" class="table table-bordered table-hover table-striped">
				<thead>
				    <tr>
					<th>Name</th>
					<th>Assigned</th>
				    </tr>
				</thead>
				<tbody>
				    <?php
				    foreach($reporting->getUsersThatHaventViewedModule($moduleId) as $user)
				    {
				    ?>
					<tr>
					    <td><?php echo $user->fname . " " . $user->lname; ?></td>
					    <td class="checkTd">
						<?php
						if($user->assigned)
						{
						    echo "✔";
						}
						else
						{
						    echo "✘";
						}
						?>
					    </td>
					</tr>
				    <?php
				    }
				    ?>
				</tbody>
			    </table>
			</div><!--End of panel-body-->
		    </div><!--End of panel-->
		</div><!--End of col-->
	    </div><!--End of row-->
	</div><!--End of content-->
    </body>
</html>