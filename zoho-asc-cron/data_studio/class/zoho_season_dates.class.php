<?php
require_once __DIR__."/db.php";
class ZOHO_SEASON_DATES extends DB
{
	private $table="zoho_season_dates";
	// *************** Get all booking start dates  *************************
	public function getAllBookingStartDates()
	{
		$qrySel="SELECT *
					FROM $this->table";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			$arrSeasonDates=[];
			if($rsltSel->num_rows>0)
			{
				while ($rowSel=$rsltSel->fetch_assoc()) 
				{
					$arrSeasonDates[$rowSel['season_year']]=$rowSel;
				}
			}
			return  $arrSeasonDates;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	public function getBookingStartDateWithSeasonYear($seasonYear)
	{
		$seasonYear=addslashes($seasonYear);
		$qrySel="SELECT *
					FROM $this->table
					WHERE season_year='{$seasonYear}'";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			return $rsltSel;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	public function getCurrentSeasonBookingStartDate()
	{
		$qrySel="SELECT *
					FROM $this->table
					WHERE is_current_season='1'
					ORDER BY booking_start_date DESC
					LIMIT 1";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			return $rsltSel;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

}

?>