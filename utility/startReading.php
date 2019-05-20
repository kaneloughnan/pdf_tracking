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
	$moduleId = $_POST['moduleId'];
	$userId = $_SESSION['userId'];
	$sessionId = session_id();
	
	$reporting->startReading($moduleId, $userId, $sessionId);
	$reporting->close();
	
	$response->status = "success";
	
	$reporting->close();
    }
    catch(PDOException $e)
    {
	$response->status = "error";
	$response->message =  $e->getMessage();
    }
    
    echo json_encode($response);
}
?>