<?php
require_once __DIR__."/db.php";
class ZOHO_DATEWISE_EVENTS extends DB
{
	private $table="zoho_datewise_events";
	// *************** Get events by rec_id of database *************************
	public function getEventsById($rec_id)
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

	// *************** Get events by season and year *************************
	public function getEventsBySeasonYear($season,$year,$order='ASC')
	{
		$season=addslashes($season);
		$year=addslashes($year);
		$qrySel="SELECT *
					FROM $this->table
					WHERE season='{$season}'
					AND year='{$year}'
					ORDER BY event_date {$order}";
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

	// *************** Get events by date*************************
	public function getEventsByDate($event_date)
	{
		$event_date=trim(addslashes($event_date));
		$qrySel="SELECT e.*,p1.name created_by_name,p2.name modified_by_name
					FROM $this->table e
					LEFT JOIN  portal_user p1
					ON e.created_by=p1.user_id
					LEFT JOIN  portal_user p2
					ON e.modified_by=p2.user_id
					WHERE e.event_date='{$event_date}'
					";
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

	// *************** Get all events *************************
	public function getAllDates()
	{
		$qrySel="SELECT e.*,p1.name created_by_name,p2.name modified_by_name
					FROM $this->table e
					LEFT JOIN  portal_user p1
					ON e.created_by=p1.user_id
					LEFT JOIN  portal_user p2
					ON e.modified_by=p2.user_id";
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

	// ***************** Function to insert a new event ***********************
	public function insertEvent($arrEvent)
	{
		$userId=0;
		if(isset($_SESSION['user_id']))
		{
			$userId=$_SESSION['user_id'];
		}
		$arrEvent['created_by']=$userId;
		$arrEvent['created_at']=date("Y-m-d H:i:s");
		$arrEvent['modified_by']=$userId;
		$arrEvent['modified_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrEvent);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a event record in database ***************
	public function updateEvent($id,$arrEvent,$arrExtraParameters=null)
	{
		$userId=0;
		if(isset($_SESSION['user_id']))
		{
			$userId=$_SESSION['user_id'];
		}
		$arrEvent['modified_by']=$userId;
		$arrEvent['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrEvent,"WHERE rec_id='{$id}'",$arrExtraParameters);
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