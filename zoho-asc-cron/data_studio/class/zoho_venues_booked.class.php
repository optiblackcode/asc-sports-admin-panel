<?php
require_once __DIR__."/db.php";
class ZOHO_VENUES_BOOKED extends DB
{
	private $table="zoho_venues_booked";
	// *************** Get vb by rec_id of database *************************
	public function getVBById($rec_id)
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

	// *************** Get VB by venues_booked_id from zoho *************************
	public function getVBByZohoId($venues_booked_id)
	{
		$venues_booked_id=trim(addslashes($venues_booked_id));
		$qrySel="SELECT *
					FROM $this->table
					WHERE venues_booked_id='{$venues_booked_id}'";
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

	// *************** Get VB with flag_complete 0 *************************
	public function getVBWithFlag()
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

	// *************** Get all venues booked *************************
	public function getAllVB()
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

	// ************** Update all VB to complete flag 1 ****************
	public function updateAllVBFlag()
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

	// ***************** Function to insert a new VB ***********************
	public function insertVB($arrVB)
	{
		$arrVB['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrVB);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a VB record in database ***************
	public function updateVB($id,$arrVB,$arrExtraParameters=null)
	{
		$arrVB['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrVB,"WHERE rec_id='{$id}'",$arrExtraParameters);
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