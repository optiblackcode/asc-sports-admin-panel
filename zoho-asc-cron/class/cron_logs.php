<?php
require_once "db.php";
class CRON_LOGS extends DB
{
	public function insert($arrCronLog)
	{
		$created=date("Y-m-d H:i:s");
		$qryIns="INSERT INTO cron_logs(cron_name, 
										is_respond, 
										created)
				 VALUES ('{$arrCronLog['cron_name']}',
							'0',
							'{$created}')";
		$rsltInsert=$this->conn->query($qryIns);
		if($rsltInsert)
		{
			$insertId=$this->conn->insert_id;
			return $insertId;
		}
		else
		{
			return false;
		}
	}	
	public function update($arrCronLog)
	{
		$qrySel="SELECT * 
					FROM cron_logs
					WHERE cron_id='{$arrCronLog['cron_id']}'";
		$rsltSel=$this->conn->query($qrySel);
		if($rsltSel->num_rows==1)
		{			
			$rowCron=$rsltSel->fetch_assoc();
			$created=$rowCron['created'];
			$modified=date("Y-m-d H:i:s");
			$tsCreated=strtotime($created);
			$tsModified=strtotime($modified);
			$responseTime=$tsModified-$tsCreated;
			$qryUpd="UPDATE cron_logs 
						SET response='{$arrCronLog['response']}',
							is_respond='1',
							modified='{$modified}',
							response_time='{$responseTime}',
							records_affected='{$arrCronLog['records_affected']}',
							errors='{$arrCronLog['errors']}',
							error_log='{$arrCronLog['error_log']}'
						WHERE cron_id='{$arrCronLog['cron_id']}'";
			$rsltUpdate=$this->conn->query($qryUpd);
			if($rsltUpdate)
			{
				return true;
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
}

?>