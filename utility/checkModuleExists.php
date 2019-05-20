<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_POST['name']))
{
    try
    {
        require_once('../class/Model.php');
	require_once('../class/Module.php');
	
	$module = new Module();
	$name = $_POST['name'];
	
	if($module->checkModuleExists($name))
	{
	    $response = "A module already exists with that name";
	}
	else
	{
	    $response = true;
	}
	
	$module->close();
    }
    catch(PDOException $e)
    {
	$response = $e->getMessage();
    }
    
    echo json_encode($response);
}
?>