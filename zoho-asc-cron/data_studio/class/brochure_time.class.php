<?php
require_once __DIR__."/db.php";
class BROCHURE_TIME extends DB
{
	private $table="is_partner_sample_data";
	//private $table2="is_partner_sample_data";
	// *************** Get talent by rec_id of database *************************
	public function getTalentById($id)
	{
		$id=addslashes($id);
		$qrySel="SELECT *
					FROM $this->table
					WHERE id='{$id}'";
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

	// *************** Get talent by rec_id of database *************************
	public function getTalentBySports($sports)
	{
		$sports=addslashes($sports);
		$qrySel="SELECT *
					FROM $this->table
					WHERE talent_sports LIKE '%{$sports}%'
					ORDER BY talent_name ASC";
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

	// *************** Get all talents *************************
	public function getAllTalents()
	{
		$qrySel="SELECT *
					FROM $this->table
					ORDER BY talent_name ASC";
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

	// *************** Get get pagewise talents *************************
	public function searchTalents($arrParams=[])
	{	
		foreach ($arrParams as $key => $value) 
		{
			$arrParams[$key]=addslashes($value);
		}

		// Limit and offset for pagination
		$qryLimitOffset="";
		if(isset($arrParams['limit']) && isset($arrParams['page']))
		{
			$limit=$arrParams['limit'];
			$page=$arrParams['page'];

			// find offset from limit and page
			$offset=($limit*($page-1));
			$qryLimitOffset=" LIMIT ".$limit." OFFSET ".$offset;
		}

		// Main query
		$qrySel="SELECT SQL_CALC_FOUND_ROWS *
					FROM $this->table";

		// Where clause
		$arrWhereClause=[];
		$qryWhereClause="";
		if(isset($arrParams['name']) && !empty($arrParams['name']))
		{
			$arrWhereClause[]="Suburb LIKE '%{$arrParams['name']}%'";
		}
		if(isset($arrParams['sports']) && !empty($arrParams['sports']))
		{
			$arrWhereClause[]="Sports LIKE '%{$arrParams['sports']}%'";
		}

		if(count($arrWhereClause)>0)
		{
			$qryWhereClause=" WHERE ".implode(" AND ", $arrWhereClause);
		}

		// Order by clause
		$orderBy=" ORDER BY Suburb ASC";

		$qrySel.=$qryWhereClause;
		$qrySel.=$orderBy;
		$qrySel.=$qryLimitOffset;
		
		$rsltSel=$this->conn->query($qrySel);

		$arrResult=[];
		if($rsltSel)
		{
			$arrResult['rslt']=$rsltSel;
			// Get total number of records
			$qrySel="SELECT FOUND_ROWS() as num_rows";
			$rsltSel=$this->conn->query($qrySel);
			if($rsltSel)
			{
				if($rowSel=$rsltSel->fetch_assoc())
				{
					$arrResult['num_rows']=$rowSel['num_rows'];
				}
			}

			return  $arrResult;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	// ***************** Function to insert a new talent ***********************
	public function insertTalent($arrTalent)
	{
		$arrTalent['created_at']=date("Y-m-d H:i:s");

		$rsltInsert=$this->common_insert($this->table,$arrTalent);
		if($rsltInsert)
		{
			return $this->conn->insert_id;
		}
		else
		{
			return false;
		}
	}

	// ************ Function to update a talent record in database ***************
	public function updateTalent($id,$arrTalent,$arrExtraParameters=null)
	{
		$arrTalent['modified_at']=date("Y-m-d H:i:s");

		if($arrExtraParameters!=null)
		{
			$arrExtraParameters['id']=$id;	
		}

		$rsltUpd=$this->common_update($this->table,$arrTalent,"WHERE id='{$id}'",$arrExtraParameters);
		if($rsltUpd)
		{
			return $rsltUpd;
		}
		else
		{
			return false;
		}
	}

	// Delete talent with database id
	public function deleteRecord($rec_id)
	{
		$rec_id=addslashes($rec_id);
		$qrySel="DELETE FROM $this->table
					WHERE rec_id='{$rec_id}'";
		$rsltDel=$this->conn->query($qrySel);
		return $rsltDel;
	}

	public function GetSampleDay($suburb,$sports,$state)
	{
		
		$qrySel="SELECT Sample_day FROM is_partner_sample_data WHERE Suburb LIKE '%$suburb%' AND Sports LIKE '%$sports%' AND State LIKE '%$state%' ";
		$rsltsampleday=$this->conn->query($qrySel);
		return $rsltsampleday;
	}	

	public function GetTime($suburb,$sports,$state)
	{
		
		$qrySel="SELECT time_data FROM is_partner_sample_data WHERE Suburb LIKE '%$suburb%' AND Sports LIKE '%$sports%' AND State LIKE '%$state%' ";
		$rsltsampleday=$this->conn->query($qrySel);
		return $rsltsampleday;
	}
	

	//***********************************28-02-2020
	public function searchTime($arrParams=[])
	{
		foreach ($arrParams as $key => $value) 
		{
			$arrParams[$key]=addslashes($value);
		}

		// Limit and offset for pagination
		$qryLimitOffset="";
		if(isset($arrParams['limit']) && isset($arrParams['page']))
		{
			$limit=$arrParams['limit'];
			$page=$arrParams['page'];

			// find offset from limit and page
			$offset=($limit*($page-1));
			$qryLimitOffset=" LIMIT ".$limit." OFFSET ".$offset;
		}

		// Main query
		$qrySel="SELECT SQL_CALC_FOUND_ROWS *
					FROM is_partner_sample_data";

		// Where clause
		$arrWhereClause=[];
		$qryWhereClause="";
		if(isset($arrParams['name']) && !empty($arrParams['name']))
		{
			$arrWhereClause[]="Suburb LIKE '%{$arrParams['name']}%'";
		}
		if(isset($arrParams['sports']) && !empty($arrParams['sports']))
		{
			$arrWhereClause[]="Sports LIKE '%{$arrParams['sports']}%'";
		}

		if(count($arrWhereClause)>0)
		{
			$qryWhereClause=" WHERE ".implode(" AND ", $arrWhereClause);
		}

		// Order by clause
		$orderBy=" ORDER BY Suburb ASC";

		$qrySel.=$qryWhereClause;
		$qrySel.=$orderBy;
		$qrySel.=$qryLimitOffset;
		$rsltSel=$this->conn->query($qrySel);

		$arrResult=[];
		if($rsltSel)
		{
			$arrResult['rslt']=$rsltSel;
			// Get total number of records
			$qrySel="SELECT FOUND_ROWS() as num_rows";
			$rsltSel=$this->conn->query($qrySel);
			if($rsltSel)
			{
				if($rowSel=$rsltSel->fetch_assoc())
				{
					$arrResult['num_rows']=$rowSel['num_rows'];
				}
			}

			return  $arrResult;
		}
		else
		{
			$this->db_error($qrySel);
			return false;
		}
	}

	public function getAllTime()
	{
		$qrySel="SELECT * FROM is_partner_sample_data";
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
}

?>