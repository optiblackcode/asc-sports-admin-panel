<?php
require_once "db.php";
class SMS_LOG extends DB
{
	public function insert($arrSms)
	{
		if(count($arrSms)>0)
		{
			$created=date("Y-m-d H:i:s");
			$modified=date("Y-m-d H:i:s");
			$qryIns="INSERT INTO sms_log(
									to_number, 
									from_number, 
									message,
									reference,
									contact_type,
									contact_id,
									sms_opt_out,
									schedule_time,
									processing_status,
									created,
									modified) VALUES ";
			$values="";
			foreach ($arrSms as $key => $arrSingleSms) {
				$to_number=addslashes($arrSingleSms['to_number']);
				$from_number=addslashes($arrSingleSms['from_number']);
				$message=addslashes($arrSingleSms['message']);
				$reference=addslashes($arrSingleSms['reference']);
				$contact_type=addslashes($arrSingleSms['contact_type']);
				$contact_id=addslashes($arrSingleSms['contact_id']);
				$sms_opt_out=addslashes($arrSingleSms['sms_opt_out']);
				$schedule_time=addslashes($arrSingleSms['schedule_time']);
				$processing_status=addslashes($arrSingleSms['processing_status']);

				$values.="('{$to_number}',
						'{$from_number}',
						'{$message}',
						'{$reference}',
						'{$contact_type}',
						'{$contact_id}',
						'{$sms_opt_out}',
						'{$schedule_time}',
						'{$processing_status}',
						'{$created}',
						'{$modified}'),";
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
				echo $this->conn->error;
				return false;
			}
		}
	}	
	public function getSMSFromQueue($type)
	{
		$qrySel="SELECT * 
					FROM sms_log
					WHERE processing_status='QUEUE'
					AND contact_type='{$type}'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function getSMSFromProcessed($type)
	{
		$qrySel="SELECT * 
					FROM sms_log
					WHERE processing_status='PROCESSED'
					AND contact_type='{$type}'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function checkPendingRequests()
	{
		$qrySel="SELECT * 
					FROM sms_log
					WHERE processing_status!='DONE'";
		$rsltSel=$this->conn->query($qrySel);
		return $rsltSel;
	}
	public function updateStatuses($id,$smsStatus,$processingStatus,$referenceNumber,$delay=0)
	{
		$smsStatus=addslashes($smsStatus);
		$processingStatus=addslashes($processingStatus);
		$referenceNumber=addslashes($referenceNumber);
		$qryUpd="UPDATE sms_log
					SET status='{$smsStatus}',
					processing_status='{$processingStatus}',
					reference='{$referenceNumber}',
					delay_min='{$delay}'
					WHERE id='{$id}'";
		$rsltUpd=$this->conn->query($qryUpd);
		return $rsltUpd;				
	}
	public function updateProcessingStatus($id,$processingStatus)
	{
		$processingStatus=addslashes($processingStatus);
		$qryUpd="UPDATE sms_log
					SET processing_status='{$processingStatus}'
					WHERE id='{$id}'";
		$rsltUpd=$this->conn->query($qryUpd);
		return $rsltUpd;				
	}
	public function GetSMSLOGManaully()
	{
		
		$qryUpd="SELECT contact_id FROM `sms_log` WHERE date(schedule_time) = '2020-08-12' AND processing_status = 'Done' AND date(created) = '2020-08-13' AND reference != '' ";
		$rsltUpd=$this->conn->query($qryUpd);
		return $rsltUpd;				
	}
}

?>