<?php
class Reporting extends Model
{
    public function __construct()
    {
	parent::__construct();
    }
    
    public function startReading($moduleId, $userId, $sessionId)
    {
	$query = "SELECT `moduleViewId` FROM `moduleview` WHERE `moduleId` = ? AND `userId` = ? AND `sessionId` = ?";
	$params = array($moduleId, $userId, $sessionId);
	
	$this->prepareStatement($query, $params);
	
	//If there are no entries for the current user, session and module (PDF or text)
	if(empty($this->fetch()))
	{
	    $query = "INSERT INTO `moduleview`(`moduleViewId`, `moduleId`, `userId`, `sessionId`, `start`, `end`) 
		VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP, NULL)";
	    $params = array($moduleId, $userId, $sessionId);

	    $this->prepareStatement($query, $params);
	}
    }
    
    public function endReading($moduleId, $userId, $sessionId)
    {
	$query = "SELECT `moduleViewId`, `end` FROM `moduleview` WHERE `moduleId` = ? AND `userId` = ? AND `sessionId` = ?";
	$params = array($moduleId, $userId, $sessionId);
	
	$this->prepareStatement($query, $params);
	
	$module = $this->fetch();
	
	//Only if an end timestamp hasn't already been made
	if(!$module->end)
	{
	    $queryModuleView = "UPDATE `moduleview` SET `end` = CURRENT_TIMESTAMP WHERE `moduleViewId` = ?";
	    $paramsModuleView = array($module->moduleViewId);

	    $this->prepareStatement($queryModuleView, $paramsModuleView);
	    
	    
	    $queryModuleAssign = "UPDATE `moduleassign` SET `viewed` = 1 WHERE `moduleId` = ? AND `userId` = ?";
	    $paramsModuleAssign = array($moduleId, $userId);

	    $this->prepareStatement($queryModuleAssign, $paramsModuleAssign);
	}
    }
    
    //When a user has successfully answered the question correctly, log the amount of attempts that it took
    public function questionAttempts($userId, $questionAttempts)
    {
	foreach($questionAttempts as $attempt)
	{
	    $query = "SELECT `quizAttemptId` FROM `quizattempt` WHERE `quizId` = ? AND `userId` = ?";
	    $params = array($attempt->quizId, $userId);

	    $this->prepareStatement($query, $params);
	    $attemptCheck = $this->fetch();
	    
	    //Only log an entry if the user hasn't attempted the question before
	    if(!$attemptCheck)
	    {
		$query = "INSERT INTO `quizattempt`(`quizAttemptId`, `quizId`, `userId`, `attempts`, `timestamp`) VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP)";
		$params = array($attempt->quizId, $userId, $attempt->attempts);

		$this->prepareStatement($query, $params);
	    }
	    
//	    if($attemptCheck)
//	    {
//		$query = "UPDATE `quizattempt` SET `attempts` = ?,`timestamp` = CURRENT_TIMESTAMP WHERE `quizattemptId` = ?";
//		$params = array($attempt->attempts, $attemptCheck->quizAttemptId);
//
//		$this->prepareStatement($query, $params);
//	    }
//	    else
//	    {
//		$query = "INSERT INTO `quizattempt`(`quizAttemptId`, `quizId`, `userId`, `attempts`, `timestamp`) VALUES (NULL, ?, ?, ?, CURRENT_TIMESTAMP)";
//		$params = array($attempt->quizId, $userId, $attempt->attempts);
//
//		$this->prepareStatement($query, $params);
//	    }
	}
    }
    
    //Checks if a specific user has read a specific module
    public function checkModuleViewed($moduleId, $userId)
    {
	$query = "SELECT `moduleViewId` FROM `moduleview` WHERE `moduleId` = ? AND `userId` = ? AND `end` IS NOT NULL";
	$params = array($moduleId, $userId);
	
	$this->prepareStatement($query, $params);
	
	if($this->rowCount())
	{
	    return true;
	}
	else
	{
	    return false;
	}
    }
    
    //Returns all users that have read a specific module
    public function getUsersThatHaveViewedModule($moduleId)
    {	
	$query = "SELECT moduleview.moduleViewId, moduleview.moduleId, moduleview.userId, moduleview.sessionId, moduleview.start, moduleview.end, 
	    user.fname, user.lname 
	    FROM `moduleview` 
	    INNER JOIN `user` 
		ON moduleview.userId = user.userId 
	    WHERE moduleview.moduleId = ? 
		AND moduleview.end IS NOT NULL 
	    ORDER BY moduleview.end DESC";
	$params = array($moduleId);

	$this->prepareStatement($query, $params);
	
	$users = $this->fetchAll();
	$response = [];
	
	//Go through all users that have viewed the module
	foreach($users as $user)
	{
	    //If the user has viewed the module a second time, check their times
	    if(isset($response[$user->userId]))
	    {
		//If this views start time is less than the one already logged, save it as the new one
		if(strtotime($user->start) < strtotime($response[$user->userId]->start))
		{
		    $response[$user->userId]->start = $user->start;
		}
		
		//If this views end time is more than the one already logged, save it as the new one
		if(strtotime($user->end) > strtotime($response[$user->userId]->end))
		{
		    $response[$user->userId]->end = $user->end;
		}
		
		///Add up the time taken
		$response[$user->userId]->timeTaken += strtotime($user->end) - strtotime($user->start);
	    }
	    else
	    {
		$response[$user->userId] = $user;
		$response[$user->userId]->timeTaken = strtotime($user->end) - strtotime($user->start);
	    }
	}
	
	return $response;
    }
    
    //Returns all users that haven't read a specific module
    public function getUsersThatHaventViewedModule($moduleId)
    {
	require_once('class/User.php');
	require_once('class/Module.php');
	
	$userClass = new User();
	$moduleClass = new Module();
	$users = $userClass->getUsers();
	$usersThatHaventViewedModule = array();
	
	foreach($users as $user)
	{
	    //If the user hasn't viewed the module (PDF or text)
	    if(!$this->checkModuleViewed($moduleId, $user->userId))
	    {
		$userObj = new stdClass();
		$userObj->fname = $user->fname;
		$userObj->lname = $user->lname;
		$userObj->assigned = $moduleClass->checkUserAssigned($moduleId, $user->userId);
		
		$usersThatHaventViewedModule[] = $userObj;
	    }
	}
	
	return $usersThatHaventViewedModule;
    }
    
    //Converts the amount of time taken to a more human readable string
    public function timeTaken($difference)
    {
        $timeTaken = new stdClass();
        $timeTaken->unix = $difference;
        
        if($difference >= 3600)
        {
            $hours = floor($difference / 3600);
            $minutes = floor(($difference / 60) % 60);
            $seconds = $difference % 60;
            
            $timeTaken->text = $hours . " " .  ngettext('hour', 'hours', $hours) . 
                    ", " . $minutes . " " .  ngettext('minute', 'minutes', $minutes) . 
                    " and " . $seconds . " " . ngettext('second', 'seconds', $seconds);
        }
        else if($difference >= 60)
        {
            $minutes = floor(($difference / 60) % 60);
            $seconds = $difference % 60;
            
            $timeTaken->text = $minutes . " " .  ngettext('minute', 'minutes', $minutes) . 
                    " and " . $seconds . " " . ngettext('second', 'seconds', $seconds);
        }
        else
        {
            $seconds = $difference % 60;
            
            $timeTaken->text = $seconds . " " . ngettext('second', 'seconds', $seconds);
        }
        
        return $timeTaken;
    }
    
    public function reportingTime($timestamp)
    {
	return date('g:i:s A j/m/Y', strtotime($timestamp));
    }
}
?>