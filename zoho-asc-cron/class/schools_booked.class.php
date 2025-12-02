<?php
require_once __DIR__."/db.php";
class SCHOOLS_BOOKED extends DB
{
	public function insert($arrAllSB)
	{
		if(count($arrAllSB)>0)
		{
			$created=date("Y-m-d H:i:s");
			$qryIns="INSERT INTO zoho_schools_booked(sb_id, 
									sb_name, 
									sb_region,
									sb_state,
									sb_cost,
									deleted,
									created,
									modified) VALUES ";
			$values="";
			foreach ($arrAllSB as $key => $arrSB) {
				$id=addslashes($arrSB['id']);
				$name=addslashes($arrSB['Name']);
				$region=addslashes($arrSB['Region']);
				$state=addslashes($arrSB['State']);
				$cost=addslashes($arrSB['Advertising_Cost']);

				$values.="('{$id}',
						'{$name}',
						'{$region}',
						'{$state}',
						'{$cost}',
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
	}	
	public function getAll()
	{
		$qrySel="SELECT * 
					FROM zoho_schools_booked
					WHERE deleted='0'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function deleteAll()
	{
		$modified=date("Y-m-d H:i:s");
		$qryDel="UPDATE zoho_schools_booked
					SET deleted='1',
						modified='{$modified}'";
		$rsltDel=$this->conn->query($qryDel);
		return $rsltDel;				
	}
}

?>