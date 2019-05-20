<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_SESSION['userId']))
{
    try
    {
        require_once('../class/Model.php');
	require_once('../class/Module.php');
	require_once('../class/Reporting.php');
	
	$moduleClass = new Module();
	$reporting = new Reporting();
	$response = new stdClass();
	
	if(isset($_POST['userId']))
	{
	    $userId = $_POST['userId'];
	}
	else
	{
	    $userId = $_SESSION['userId'];
	}
	
	$response->status = "success";
	$response->data = $moduleClass->getAssignedModules($userId);
	
	$moduleClass->close();
    }
    catch(PDOException $e)
    {
	$response->status = "error";
	$response->message =  $e->getMessage();
    }
    
    echo json_encode($response);
}
?>