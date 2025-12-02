<?php
require_once __DIR__."/db.php";
class ZOHO_SUBURBS extends DB
{
	private $table="zoho_suburbs";
	// *************** Get suburb by rec_id of database *************************
	public function getSuburbById($rec_id)
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

	// *************** Get suburb by suburb id from zoho *************************
	public function getSuburbByZohoId($suburb_id)
	{
		$suburb_id=trim(addslashes($suburb_id));
		$qrySel="SELECT *
					FROM $this->table
					WHERE suburb_id='{$suburb_id}'";
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

	// *************** Get all suburbs *************************
	public function getAllSuburbs()
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

	// *************** Get suburb with flag_complete 0 *************************
	public function getSuburbsWithFlag()
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

	// ************** Update all suburbs to complete flag 1 ****************
	public function updateAllSuburbsFlag()
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

	// ***************** Function to insert a new suburb ***********************
	public function insertSuburb($arrSuburb)
	{
		$arrSuburb['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrSuburb);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a suburb record in database ***************
	public function updateSuburb($id,$arrSuburb,$arrExtraParameters=null)
	{
		$arrSuburb['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrSuburb,"WHERE rec_id='{$id}'",$arrExtraParameters);
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