<?php
class User extends Model
{
    public function __construct()
    {
	parent::__construct();
    }
    
    public function addUser($username, $password, $fname, $lname, $email, $admin)
    {
	$hash = $this->hashPassword($password);
	$query = "INSERT INTO `user`(`userId`, `username`, `password`, `fname`, `lname`, `email`, `admin`, `deleted`, `timestamp`) VALUES (NULL, ?, ?, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP)";
	$params = array($username, $hash, $fname, $lname, $email, $admin);
	
	$this->prepareStatement($query, $params);
	
	return $this->getLastInsertId();
    }
    
    public function getUserDetails($userId)
    {
	$query = "SELECT `username` AS 'Username', `fname` AS 'First name', `lname` AS 'Last name', `email` AS 'Email' FROM `user` WHERE `userId` = ?";
	$params = array($userId);
	
	$this->prepareStatement($query, $params);
	
	return $this->fetch();
    }
    
    public function editPassword($userId, $username, $password)
    {
	$hash = $this->hashPassword(md5($username . $password));
	$query = "UPDATE `user` SET `password` = ? WHERE `userId` = ?";
	$params = array($hash, $userId);
	
	$this->prepareStatement($query, $params);
    }
    
    public function getUsers()
    {
	$query = "SELECT `userId`, `username`, `password`, `fname`, `lname`, `email`, `deleted`, `timestamp` FROM `user` WHERE `deleted` = 0";
	
	$this->query($query);
	
	return $this->fetchAll();
    }
    
    public function checkUserExists($column, $value)
    {
	$query = "SELECT `userId` FROM `user` WHERE `$column` = ? AND `deleted` = 0";
	$params = array($value);
	
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
    
    public function checkUserLogin($username)
    {
	$query = "SELECT `userId`, `username`, `password`, `admin` FROM `user` WHERE `username` = ? AND `deleted` = 0";
	$params = array($username);
	
	$this->prepareStatement($query, $params);
	
	$user = $this->fetch();
	
	if($user)
	{
	    return $user;
	}
	else
	{
	    return false;
	}
    }
    
    private function hashPassword($password)
    {
	return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public function emailUser($username, $password, $fname, $lname, $email)
    {
	//Relative to minissh PHPMailer location (DON'T CHANGE)
	require_once('PHPMailer/PHPMailerAutoload.php');

	$mail = new PHPMailer; // the true param means it will throw exceptions on errors, which we need to catch
	$response = new stdClass();

	$mail->IsSMTP(); // telling the class to use SMTP

	try
	{
	    $mail->Host       = "ssl://smtp.gmail.com";             // SMTP server
	    $mail->SMTPDebug  = false;                              // enables SMTP debug information (for testing)
	    $mail->SMTPAuth   = true;                               // enable SMTP authentication
	    $mail->Port       = 465;                                // set the SMTP port for the GMAIL server
	    $mail->Username   = "";         // SMTP account username
	    $mail->Password   = "";        // SMTP account password
	    $mail->AddReplyTo('', 'No-Reply');
	    $mail->AddAddress($email, 'PDF Tracking and Agreement');
	    $mail->SetFrom('', 'PDF Tracking and Agreement');

	    $message = '<p>This email address has been added to the PDF Tracking and Agreement website along with the following details:</p> '
		    . '<p><strong>Username:</strong> ' . $username . '</p>'
		    . '<p><strong>Password:</strong> ' . $password . '</p>'
		    . '<p><strong>First name:</strong> ' . $fname . '</p>'
		    . '<p><strong>Last name:</strong> ' . $lname . '</p>'
		    . '<p>You can login at: {URL HERE}</p>';

	    $mail->Subject = "PDF Tracking and Agreement";
	    $mail->MsgHTML($message);

	    //$mail->Send(); Disabled for testing

	    $response->status = "success";
	}
	catch (phpmailerException $e)
	{
	    $response->status = "error";
	    $response->message = $e->errorMessage(); //Pretty error messages from PHPMailer
	}
	catch (Exception $e)
	{
	    $response->status = "error";
	    $response->message = $e->getMessage(); //Boring error messages from anything else!
	}
	
	return $response;
    }
}
?>