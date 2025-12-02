<?php
require_once "db.php";
class VENUES extends DB
{
	public function insertOrUpdate($arrVenue)
	{
		$created=date("Y-m-d H:i:s");
		$qrySel="SELECT * 
					FROM zoho_venues
					WHERE zoho_id='{$arrVenue['zoho_id']}'";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel)
		{
			if($rsltSel->num_rows==0)
			{
				$qryIns="INSERT INTO zoho_venues(zoho_id, 
										venue_name, 
										created,
										modified)
					VALUES ('{$arrVenue['zoho_id']}',
							'{$arrVenue['venue_name']}',
							'{$created}',
							'{$created}')";
				$rsltInsert=$this->conn->query($qryIns);
				if($rsltInsert)
				{
					$insertId=$this->conn->insert_id;
					return $rsltInsert;
				}
				else
				{
					return false;
				}
			}
			else
			{
				$row=$rsltSel->fetch_assoc();
				$id=$row['id'];
				$qryUpd="UPDATE zoho_venues 
							SET zoho_id='{$arrVenue['zoho_id']}',
								venue_name='{$arrVenue['venue_name']}',
								modified='{$created}' 
							WHERE id='$id' ";
				$rsltUpd=$this->conn->query($qryUpd);
				return $rsltUpd;	
			}
		}
		else
		{
			return false;
		}
	}	
	public function getAll()
	{
		$qrySel="SELECT * 
					FROM zoho_venues
					ORDER BY venue_name";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function delete($zoho_id)
	{
		$qryDel="DELETE FROM zoho_venues
					WHERE zoho_id='$zoho_id'";
		$rsltDel=$this->conn->query($qryDel);
		return $rsltDel;				
	}
}

?>