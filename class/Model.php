<?php
class Model extends PDO
{
    public $pd;
    public $db;
    
    public function __construct()
    {
	$database = "pdf_tracking";
	$username = "root";
	$password = "";
    
	//Accessing the database
	$this->db = new PDO("mysql:host=localhost;dbname=$database", $username, $password);

	$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Sets PDO error mode
	$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);//Sets PDFO fetch mode, fetch data as objects)
    }
    
    //Simplifies how to execute simple queries
    public function query($query)
    {
	$this->pd = $this->db->query($query);
    }
    
    //Prepare statement with bind values and then execute
    public function prepareStatement($query, $params)
    {
	$this->pd = $this->db->prepare($query);
	
	for($i = 0; $i < count($params); $i++)
	{
	    $this->pd->bindValue($i + 1, $params[$i]);
	}
	
	$this->pd->execute();
    }
    
    //Fetch single row
    public function fetch()
    {
	return $this->pd->fetch();
    }
    
    //Fetch all rows
    public function fetchAll()
    {
	return $this->pd->fetchAll();
    }
    
    //Count all rows
    public function rowCount()
    {
	return $this->pd->rowCount();
    }
    
    //Get the last inserted ID
    public function getLastInsertId()
    {
	return $this->db->lastInsertId();
    }
    
    //Closes the connection by setting the db object to null
    public function close()
    {
	$this->db = null;
    }
}
?>