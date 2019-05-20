<?php
class Quiz extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addQuiz($question,$moduleId,$answers)
    {
        $query = "INSERT INTO `quiz`(`quizId`, `moduleId`, `question`, `answers`)
	    VALUES (NULL, ?, ?, ?)";
        $params = array($moduleId, $question, $answers);

        $this->prepareStatement($query, $params);
    }

    public function checkQuizExists($name)
    {
        $query = "SELECT `quizId` FROM `quiz` WHERE `name` = ?";
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

    public function getQuizes()
    {
        $query = "SELECT `quizId`, `moduleId`, `question`, `answers` FROM `quiz`";

        $this->query($query);

        return $this->fetchAll();
    }

    public function getQuizDetails($moduleId)
    {
        $query = "SELECT `quizId`, `moduleId`, `question`, `answers` FROM `quiz` WHERE `moduleId` = ?";
        $params = array($moduleId);

        $this->prepareStatement($query, $params);

        return $this->fetchAll();
    }
    
    public function getQuizAttempt($quizId, $userId)
    {
	$query = "SELECT `quizAttemptId`, `quizId`, `userId`, `attempts`, `timestamp` FROM `quizattempt` WHERE `quizId` = ? AND `userId` = ?";
        $params = array($quizId, $userId);

        $this->prepareStatement($query, $params);

        return $this->fetch();
    }
}
?>