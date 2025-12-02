<?php
require_once __DIR__."/db.php";
class SPORTS_ACTIVITY_DATA extends DB
{
	private $table="sports_activity";
	
	// *************** Get all events *************************
	public function getFixData()
	{ 
		$qrySel="SELECT * FROM $this->table WHERE activity_type = '0' ORDER BY event_date ASC";
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

	public function getSportActivityBySport($sport_name)
	{ 
		
		$qrySel="SELECT * FROM $this->table WHERE  sports_name='".$sport_name."'";
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

	public function getSportDataByID($rec_id,$activity_type)
	{ 
		
		$qrySel="SELECT activity_sort_name FROM $this->table WHERE rec_id=".$rec_id." AND activity_type=".$activity_type."";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			$row = $rsltSel->fetch_assoc();
  			return $row['activity_sort_name'];
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	
	
}

?>