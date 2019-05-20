<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_SESSION['userId']))
{
    try
    {
        require_once('../class/Model.php');
	require_once('../class/User.php');
	
	$user = new User();
	$response = new stdClass();
	
	$response->status = "success";
	$response->data = $user->getUsers();
	
	$user->close();
    }
    catch(PDOException $e)
    {
	$response->status = "error";
	$response->message =  $e->getMessage();
    }
    
    echo json_encode($response);
}
?>