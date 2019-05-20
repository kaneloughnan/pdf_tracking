<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_POST['username']))
{
    try
    {
        require_once('../class/Model.php');
	require_once('../class/User.php');
	
	$user = new User();
	$response = new stdClass();
	$response->status = "fail";
	$username = $_POST['username'];
	$password = md5($username . $_POST['password']);//Hashes the username and password with md5
	$checkUser = $user->checkUserLogin($username);

	//If a user with the entered username is found, check to see if their password is correct
	if($checkUser)
	{
	    if(password_verify($password, $checkUser->password))
	    {
		$_SESSION['userId'] = $checkUser->userId;
		$_SESSION['username'] = $checkUser->username;
		$_SESSION['admin'] = $checkUser->admin;
		$response->status = "success";
	    }
	}
	
	$user->close();
    }
    catch(PDOException $e)
    {
        $response->status = "error";
	$response->message = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>