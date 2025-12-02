<?php
require_once __DIR__."/db.php";
class ZOHO_NOTIFY_ME_SPORTS_STATE extends DB
{
	private $table="zoho_notify_me_sports_state";
	// *************** Get prospect by rec_id of database *************************
	public function getRecordsByProspectId($prospect_id)
	{
		$prospect_id=addslashes($prospect_id);
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