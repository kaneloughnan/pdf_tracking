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
	$userId = $_SESSION['userId'];
	$userDetails = $user->getUserDetails($userId);
	$username = $userDetails->Username;
	$password = $_POST['password'];
	
	$user->editPassword($userId, $username, $password);
	$user->close();
	
	$response->status = "success";
    }
    catch(PDOException $e)
    {
        $response->status = "error";
	$response->message = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>