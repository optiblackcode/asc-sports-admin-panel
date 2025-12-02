<?php
require_once __DIR__."/db.php";
class CAMPS extends DB
{
	public function insert($arrAllCamps)
	{
		if(count($arrAllCamps)>0)
		{
			$created=date("Y-m-d H:i:s");
			$qryIns="INSERT INTO zoho_camps(zoho_camp_id, 
									zoho_camp_name, 
									camp_status,
									camp_region,
									camp_state,
									school_advertising_cost,
									total_cost,
									earned_revenue,
									deleted,
									created,
									modified) VALUES ";
			$values="";
			foreach ($arrAllCamps as $key => $arrCamp) {
				$id=addslashes($arrCamp['id']);
				$name=addslashes($arrCamp['Name']);
				$status=addslashes($arrCamp['Camp_Status']);
				$region=addslashes($arrCamp['Region']);
				$state=addslashes($arrCamp['State']);
				$school_advertising_cost=addslashes($arrCamp['School_Advertising_Cost']);
				$total_cost=addslashes($arrCamp['Total_Cost']);
				$earned_revenue=addslashes($arrCamp['Earned_Revenue']);

				$values.="('{$id}',
						'{$name}',
						'{$status}',
						'{$region}',
						'{$state}',
						'{$school_advertising_cost}',
						'{$total_cost}',
						'{$earned_revenue}',
						'0',
						'{$created}',
						'{$created}'),";
			}
			$values=trim($values,",");
			$qryIns.=$values;
			
			$rsltInsert=$this->conn->query($qryIns);
			if($rsltInsert)
			{
				return $rsltInsert;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}	
	public function getCampsByRegion($region)
	{
		$qrySel="SELECT *
					FROM zoho_camps
					WHERE camp_region='{$region}'
					AND deleted='0'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function getCampByZohoId($camp_id)
	{
		$qrySel="SELECT *
					FROM zoho_camps
					WHERE zoho_camp_id='{$camp_id}'
					AND deleted='0'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function getCampsByState($state)
	{
		$qrySel="SELECT *
					FROM zoho_camps
					WHERE camp_state='{$state}'
					AND deleted='0'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function getAll()
	{
		$qrySel="SELECT * 
					FROM zoho_camps
					WHERE deleted='0'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function deleteAll()
	{
		$modified=date("Y-m-d H:i:s");
		$qryDel="UPDATE zoho_camps
					SET deleted='1',
					modified='{$modified}'";
		$rsltDel=$this->conn->query($qryDel);
		return $rsltDel;				
	}
}

?>