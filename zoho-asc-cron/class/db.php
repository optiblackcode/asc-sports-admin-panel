<?php
class DB
{
	protected $conn;
	public function __construct()
	{
		$host="localhost";
		$username="root";
		$password="muHVAR.7K^E?+xB;4";
		$db="asc";

		$this->conn=new mysqli($host,$username,$password,$db);
		if($this->conn->connect_error)
		{
			die("Error in DB connection.");	
		}
		
	}
}
?>