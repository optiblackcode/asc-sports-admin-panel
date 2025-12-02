<?php
require_once __DIR__."/db.php";
class ZOHO_CAMPS extends DB
{
	private $table="zoho_camps";
	// *************** Get camp by rec_id of database *************************
	public function getCampById($rec_id)
	{
		$rec_id=addslashes($rec_id);
		$qrySel="SELECT *
					FROM $this->table
					WHERE rec_id='{$rec_id}'";
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

	// *************** Get camp by camp id from zoho *************************
	public function getCampByZohoId($camp_id)
	{
		$camp_id=trim(addslashes($camp_id));
		$qrySel="SELECT *
					FROM $this->table
					WHERE camp_id='{$camp_id}'";
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

	// *************** Get camp by season and year of database *************************
	public function getCampsBySeasonYear($season,$year)
	{
		$season=addslashes($season);
		$year=addslashes($year);
		$qrySel="SELECT *
					FROM $this->table
					WHERE season='{$season}'
					AND year='{$year}'
					ORDER BY camp_name ASC";
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

	// *************** Get camp with flag_complete 0 *************************
	public function getCampsWithFlag()
	{
		$qrySel="SELECT *
					FROM $this->table
					WHERE flag_complete='0'";
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

	// *************** Get all camps *************************
	public function getAllCamps()
	{
		$qrySel="SELECT *
					FROM $this->table";
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

	// ************** Update all camp to complete flag 1 ****************
	public function updateAllCampsFlag()
	{
		$qryUpd="UPDATE $this->table
					SET flag_complete='1'
					WHERE flag_complete='0'";
		$rsltUpd=$this->conn->query($qryUpd);
		if($rsltUpd)
		{
			return $rsltUpd;
		}
		else
		{
			$this->db_error($qryUpd);
			return false;
		}
	}

	// ***************** Function to insert a new camp ***********************
	public function insertCamp($arrCamp)
	{
		$arrCamp['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrCamp);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a camp record in database ***************
	public function updateCamp($id,$arrCamp,$arrExtraParameters=null)
	{
		$arrCamp['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrCamp,"WHERE rec_id='{$id}'",$arrExtraParameters);
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