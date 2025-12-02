<?php

$host="localhost";
		$username="root";
		$password="iRg7QOwKmTdO10mB";
		$db="asc_datastudio_reporting";

		$conn = mysqli_connect($host,$username,$password,$db);

		if(!$conn)
		{
			die("Error in DB connection.");	
		}

?>