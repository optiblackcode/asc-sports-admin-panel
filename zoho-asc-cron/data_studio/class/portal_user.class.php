<?php
require_once __DIR__."/db.php";
class PORTAL_USER extends DB
{
	public $loginPageUrl="index.php";
	public $dashboardUrl="dashboard.php";
	private $table="portal_user";
	// *************** Get user by user_id of database ********************************
	public function getUserById($user_id)
	{
		$user_id=addslashes($user_id);
		$qrySel="SELECT *
					FROM $this->table
					WHERE user_id='{$user_id}'";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			return  $rsltSel;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	// *************** Check if login exists or not ********************************
	public function checkLogin()
	{
		if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
		{
			return true;	
		}
		else
		{
			return false;
		}
	}

	// ***************** Function to insert a new user ***********************
	public function insertUser($arrUser)
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

	// ***************** Function to check credentials ***********************
	public function checkCredentials($arrUser)
	{
		$username=addslashes(trim($arrUser['username']));
		$qrySel="SELECT *
					FROM $this->table
					WHERE username='{$username}'";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			if($rsltSel->num_rows==0)
			{
				return false;
			}
			else if($rsltSel->num_rows==1)
			{
				$rowSel=$rsltSel->fetch_assoc();
				return $rowSel;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	// ************ Function to update a user record in database *********************
	public function updateUser($id,$arrUser,$arrExtraParameters=null)
	{
		$arrUser['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrUser,"WHERE user_id='{$id}'",$arrExtraParameters);
		if($rsltUpd)
		{
			return $rsltUpd;
		}
		else
		{
			return false;
		}
	}
	
}

?>