<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_POST['moduleId']))
{
    try
    {
	require_once('../class/Model.php');
        require_once('../class/Reporting.php');
	
	$reporting = new Reporting();
	$response = new stdClass();
	$userId = $_SESSION['userId'];
	$questionAttempts = json_decode($_POST['questionAttempts']);
	
	$reporting->questionAttempts($userId, $questionAttempts);
	$reporting->close();	
	
	$response->status = "success";
    }
    catch(PDOException $e)
    {
	$response->status = "error";
	$response->message =  $e->getMessage();
    }
    
    echo json_encode($response);
}
?>