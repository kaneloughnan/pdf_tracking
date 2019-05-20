<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_POST['column']))
{
    try
    {
        require_once('../class/Model.php');
	require_once('../class/User.php');
	
	$user = new User();
	$column = $_POST['column'];
	$value = $_POST['value'];
	
	if($user->checkUserExists($column, $value))
	{
	    $response = "A user already exists with that $column";
	}
	else
	{
	    $response = true;
	}
	
	$user->close();
    }
    catch(PDOException $e)
    {
	$response = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>