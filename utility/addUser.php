<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_POST['username']))
{
    try
    {
        require_once('../class/Model.php');
	require_once('../class/User.php');
	require_once('../class/Module.php');
	
	$user = new User();
	$module = new Module();
	$response = new stdClass();
	$username = $_POST['username'];
	$password = md5($username . $_POST['password']);
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$admin = $_POST['admin'];
	$moduleIds = json_decode($_POST['moduleIds'], true);
	
	$userId = $user->addUser($username, $password, $fname, $lname, $email, $admin);
	$module->assignModules($userId, $moduleIds);
	$emailSuccess = $user->emailUser($username, $_POST['password'], $fname, $lname, $email);
	$user->close();
	
	//Checks if PHPMailer's email was sent successfully
	if($emailSuccess->status === "success")
	{
	    $response->status = "success";
	}
	else
	{
	    $response->status = "error";
	    $response->message = $emailSuccess->message;
	}
    }
    catch(PDOException $e)
    {
        $response->status = "error";
	$response->message = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>