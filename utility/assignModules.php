<?php
header('Content-type:application/json;charset=utf-8');

session_start();

if(isset($_POST['userId']))
{
    try
    {
        require_once('../class/Model.php');
        require_once('../class/Module.php');
	
        $module = new Module();
        $response = new stdClass();
	$userId = $_POST['userId'];
        $moduleIds = json_decode($_POST['moduleIds'], true);

        $module->assignModules($userId, $moduleIds);
	$module->close();

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