<?php
class Module extends Model
{
    public function __construct()
    {
	parent::__construct();
    }
    
    public function addModule($name, $text, $pdf, $quiz)
    {
	$query = "INSERT INTO `module`(`moduleId`, `name`, `text`, `pdf`, `quiz`, `deleted`, `timestamp`)
	    VALUES (NULL, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP)";
	$params = array($name, $text, $pdf, $quiz);
	
	$this->prepareStatement($query, $params);
    }
    
    public function checkModuleExists($name)
    {
	$query = "SELECT `moduleId` FROM `module` WHERE `name` = ?";
	$params = array($name);
	
	$this->prepareStatement($query, $params);
	
	//If a user(s) is found, return true
	if($this->rowCount())
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    public function getModules()
    {
	$query = "SELECT `moduleId`, `name`, `text`, `pdf`, `quiz`, `timestamp` FROM `module` WHERE `deleted` = 0";
	
	$this->query($query);
	
	return $this->fetchAll();
    }
    
    public function checkUserAssigned($moduleId, $userId)
    {
	$query = "SELECT `moduleAssignId` FROM `moduleassign` WHERE `moduleId` = ? AND `userId` = ?";
	$params = array($moduleId, $userId);
	
	$this->prepareStatement($query, $params);
	
	return $this->rowCount();
    }
    
    public function getAssignedModules($userId)
    {
	$query = "SELECT moduleassign.moduleAssignId, moduleassign.moduleId, moduleassign.viewed, module.name, module.pdf, module.quiz 
	    FROM `moduleassign` 
	    INNER JOIN `module` 
		ON moduleassign.moduleId = module.moduleId 
	    WHERE moduleassign.userId = ?";
	$params = array($userId);
	
	$this->prepareStatement($query, $params);
	
	return $this->fetchAll();
    }
    
    public function getModuleDetails($moduleId)
    {
	$query = "SELECT `moduleId`, `name`, `text`, `pdf`, `quiz`, `deleted`, `timestamp` FROM `module` WHERE `moduleId` = ?";
	$params = array($moduleId);
	
	$this->prepareStatement($query, $params);
	
	return $this->fetch();
    }
    
    public function assignModules($userId, $moduleIds)
    {
	foreach($moduleIds as $moduleId)
	{
	    $query = "INSERT INTO `moduleassign`(`moduleAssignId`, `moduleId`, `userId`, `viewed`, `timestamp`) VALUES (NULL, ?, ?, 0, CURRENT_TIMESTAMP)";
	    $params = array($moduleId, $userId);

	    $this->prepareStatement($query, $params);
	}
    }
}
?>