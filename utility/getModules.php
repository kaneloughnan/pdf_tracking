<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_SESSION['userId']))
{
    try
    {
        require_once('../class/Model.php');
	require_once('../class/Module.php');
	
	$module = new Module();
	$response = new stdClass();
	
	$response->status = "success";
	$response->data = $module->getModules();
	
	$module->close();
    }
    catch(PDOException $e)
    {
	$response->status = "error";
	$response->message =  $e->getMessage();
    }
    
    echo json_encode($response);
}
?>