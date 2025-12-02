<?php
require_once __DIR__."/db.php";
class ZOHO_PARTICIPANTS extends DB
{
	private $table="zoho_participants";
	// *************** Get participant by rec_id of database *************************
	public function getParticipantById($rec_id)
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

	// *************** Get participant by participant id from zoho *************************
	public function getParticipantByZohoId($participant_id)
	{
		$participant_id=trim(addslashes($participant_id));
		$qrySel="SELECT *
					FROM $this->table
					WHERE participant_id='{$participant_id}'";
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

	// *************** Get all participants *************************
	public function getAllParticipants()
	{
		$qrySel="SELECT p.*,c.season as camp_season,c.year as camp_year
					FROM $this->table p
					LEFT JOIN zoho_camps c
					ON p.camp_id=c.camp_id";
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

	// *************** Get participant with camp_id *************************
	public function getParticipantsByZohoCampId($campId)
	{
		$qrySel="SELECT *
					FROM $this->table
					WHERE camp_id='{$campId}'";
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

	// *************** Get participant with flag_complete 0 *************************
	public function getParticipantsWithFlag()
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

	// *************** Get maximum day number from season and year ******************
	public function getMaxDayFromSeasonYear($season,$year)
	{
		$qrySel="SELECT max(calculated_day_of_season) as max_day
					FROM $this->table
					WHERE season='{$season}'
					AND year='{$year}'";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			if($rowSel=$rsltSel->fetch_assoc())
			{
				return $rowSel['max_day'];
			}
			else
			{
				return 0;
			}
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	// ************** Update all participants to complete flag 1 ****************
	public function updateAllParticipantFlag()
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

	// ***************** Function to insert a new participant ***********************
	public function insertParticipant($arrParticipant)
	{
		$arrParticipant['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrParticipant);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a participant record in database ***************
	public function updateParticipant($id,$arrParticipant,$arrExtraParameters=null)
	{
		$arrParticipant['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrParticipant,"WHERE rec_id='{$id}'",$arrExtraParameters);
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