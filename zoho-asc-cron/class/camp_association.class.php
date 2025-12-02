<?php
require_once __DIR__."/db.php";
class CAMP_ASSOCIATION extends DB
{
	public function insert($arrCampAssociation)
	{
		if(count($arrCampAssociation)>0)
		{
			$created=date("Y-m-d H:i:s");
			$qryIns="INSERT INTO zoho_camp_sb_association(zoho_camp_id, 
									zoho_camp_name, 
									sb_id,
									sb_name,
									sb_cost,
									region,
									state,
									deleted,
									created,
									modified) VALUES ";
			$values="";
			foreach ($arrCampAssociation as $key => $arrAssociation) {
				$camps_id=addslashes($arrAssociation['camps_id']);
				$camp_name=addslashes($arrAssociation['camp_name']);
				$sb_id=addslashes($arrAssociation['sb_id']);
				$sb_name=addslashes($arrAssociation['sb_name']);
				$sb_cost=addslashes($arrAssociation['sb_cost']);
				$region=addslashes($arrAssociation['region']);
				$state=addslashes($arrAssociation['state']);

				$values.="('{$camps_id}',
						'{$camp_name}',
						'{$sb_id}',
						'{$sb_name}',
						'{$sb_cost}',
						'{$region}',
						'{$state}',
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
	public function getAll()
	{
		$qrySel="SELECT * 
					FROM zoho_camp_sb_association
					WHERE deleted='0'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function deleteAll()
	{
		$modified=date("Y-m-d H:i:s");
		$qryDel="UPDATE zoho_camp_sb_association
					SET deleted='1',
					modified='{$modified}'";
		$rsltDel=$this->conn->query($qryDel);
		return $rsltDel;				
	}
}

?>