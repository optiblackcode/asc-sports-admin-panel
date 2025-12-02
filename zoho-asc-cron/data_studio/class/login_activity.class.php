<?php
require_once __DIR__."/db.php";
class LOGIN_ACTIVITY extends DB
{
	private $table="login_activity";
	// ***************** Function to insert a new user ***********************
	public function insertActivity($arrUser)
	{
		$arrUser['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrUser);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}	
}

?>