<?php
require_once __DIR__."/db.php";
class ZOHO_PROSPECTS extends DB
{
	private $table="zoho_prospects";
	// *************** Get prospect by rec_id of database *************************
	public function getProspectById($rec_id)
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

	// *************** Get prospect by prospect id from zoho *************************
	public function getProspectByZohoId($prospect_id)
	{
		$prospect_id=trim(addslashes($prospect_id));
		$qrySel="SELECT *
					FROM $this->table
					WHERE prospect_id='{$prospect_id}'";
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

	// *************** Get all prospects *************************
	public function getAllProspects()
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

	// *************** Get prospect with flag_complete 0 *************************
	public function getProspectsWithFlag()
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

	// ************** Update all prospects to complete flag 1 ****************
	public function updateAllProspectsFlag()
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

	// ***************** Function to insert a new prospect ***********************
	public function insertProspect($arrProspect)
	{
		$arrProspect['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrProspect);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a prospect record in database ***************
	public function updateProspect($id,$arrProspect,$arrExtraParameters=null)
	{
		$arrProspect['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrProspect,"WHERE rec_id='{$id}'",$arrExtraParameters);
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