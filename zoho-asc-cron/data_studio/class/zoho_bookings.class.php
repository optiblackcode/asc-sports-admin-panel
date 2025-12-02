<?php
require_once __DIR__."/db.php";
class ZOHO_BOOKINGS extends DB
{
	private $table="zoho_bookings";
	// *************** Get booking by rec_id of database *************************
	public function getBookingById($rec_id)
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

	// *************** Get booking by booking id from zoho *************************
	public function getBookingByZohoId($booking_id)
	{
		$booking_id=trim(addslashes($booking_id));
		$qrySel="SELECT *
					FROM $this->table
					WHERE booking_id='{$booking_id}'";
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

	// *************** Get booking with flag_complete 0 *************************
	public function getBookingsWithFlag()
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

	// *************** Get all bookings *************************
	public function getAllBookings()
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

	// ************** Update all bookings to complete flag 1 ****************
	public function updateAllBookingsFlag()
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

	// ***************** Function to insert a new booking ***********************
	public function insertBooking($arrBooking)
	{
		$arrBooking['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrBooking);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a booking record in database ***************
	public function updateBooking($id,$arrBooking,$arrExtraParameters=null)
	{
		$arrBooking['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrBooking,"WHERE rec_id='{$id}'",$arrExtraParameters);
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