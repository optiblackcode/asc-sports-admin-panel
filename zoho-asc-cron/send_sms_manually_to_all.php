<?php
ini_set('max_execution_time', 10800); // 3 hours
ini_set("memory_limit", "-1");
set_time_limit(0);
include __DIR__."/curl_zoho/class/zoho_methods.class.php";
include __DIR__."/class/sms_log.class.php";
include __DIR__."/send_sms_common.php";
$objSMSLog=new SMS_LOG();
$objZoho=new ZOHO_METHODS();
if($objZoho->checkTokens())
{
// Get all sms from database and call api for sms broadcast
	$rsltSmsLog=$objSMSLog->getSMSFromQueue('parent');
	while($rowSmsLog=$rsltSmsLog->fetch_assoc())
	{
		$delayMin=0;
		$processingStatus="PROCESSED";
		$id=$rowSmsLog['id'];
		$to=$rowSmsLog['to_number'];
		$source=$rowSmsLog['from_number'];
		$message=$rowSmsLog['message'];
		$reference_number=$id;
		$smsbroadcast_response="";
		if($rowSmsLog['sms_opt_out'])
		{
		}
		else
		{
			if(trim($rowSmsLog['schedule_time'])!="")
			{
				$sendingTime=strtotime($rowSmsLog['schedule_time']);
				$currentTime=time();
				$delayMin=ceil(($sendingTime-$currentTime)/60);
				if($delayMin < 0)
				{
					$delayMin=0;
				}
				$content =  'username='.rawurlencode($username).
					            '&password='.rawurlencode($password).
					            '&to='.rawurlencode($to).
					            '&from='.rawurlencode($source).
					            '&message='.rawurlencode($message).
					            '&ref='.rawurlencode($reference_number)."&delay=".$delayMin;
		        $smsbroadcast_response = sendSMS($content);
			}
			else
			{
				$content =  'username='.rawurlencode($username).
				            '&password='.rawurlencode($password).
				            '&to='.rawurlencode($to).
				            '&from='.rawurlencode($source).
				            '&message='.rawurlencode($message).
				            '&ref='.rawurlencode($reference_number);
		        $smsbroadcast_response = sendSMS($content);
			}
		}
		$status="";
		$reference="";
		if($processingStatus=="PROCESSED")
		{
			$arrResponse=[];
			$arrResponse=explode(':',$smsbroadcast_response);
			if(count($arrResponse)>1)
			{
				$status=$arrResponse[0];
				$reference=$smsbroadcast_response;
			}
		}
		$update = $objSMSLog->updateStatuses($id,$status,$processingStatus,$reference,$delayMin);
		if($update)
		{

		}
		else
		{
			echo "Error in updating record.";

			$myFile = "sms_error_log.txt";
			$fh = fopen($myFile, 'a');
			$stringData = "================================================================\n";
			$stringData.= "----------------------------------------------------------------\n";
			$stringData.="SMS Response:".$smsbroadcast_response."\n";
			$stringData.="Update Record:".$update."\n";
			$stringData.="status:".$status."\n";
			$stringData.="reference:".$reference."\n";
			$stringData .= "---------------------------------------------------------------\n";
			fwrite($fh, $stringData);
			fclose($fh);

		}
	}
}
if($objZoho->checkTokens())
{
	// Get processed sms from databases
	$recordCount=0;
	$arrAllSmsRecords=[];
	$arrAllParents=[];
	$rsltProcessesSms=$objSMSLog->getSMSFromProcessed('parent');
	while($rowProcesses=$rsltProcessesSms->fetch_assoc())
	{
		// Update status to done
		$objSMSLog->updateProcessingStatus($rowProcesses['id'],"DONE");

		// ***************************************
		$arrSmsRecord=[];
		$arrSmsRecord['Message_Content']=$rowProcesses['message'];
		$arrSmsRecord['To_Number']=checkMobileNumber($rowProcesses['to_number']);
		$arrSmsRecord['Type']='Sent';
		$arrSmsRecord['Parent_Name']=$rowProcesses['contact_id'];
		if($rowProcesses['status']=="OK")
		{
			$arrSmsRecord['Sent_Status']='Success';
		}
		else
		{
			$arrSmsRecord['Sent_Status']='Fail';
		}
		$arrAllSmsRecords[]=$arrSmsRecord;
		// **************************************
		$arrSingleParent=[];
		$arrSingleParent['id']=$rowProcesses['contact_id'];
		$arrSingleParent['SMS_Text']='';
		$arrSingleParent['Send_Now']=false;
		$arrSingleParent['Schedule_SMS']='';
		$arrSingleParent['SMS_Text']='';
		$arrAllParents[]=$arrSingleParent;
		// **********************************************
		$recordCount++;
		if($recordCount>=95)
		{
			// Create records in sms records
			$recordCount=0;
			$resp=$objZoho->insertRecord("SMS_Records",$arrAllSmsRecords);
			$arrAllSmsRecords=[];
			// Clear sms details in parents
			$resp=$objZoho->bulkUpdateRecords("Parent_Guardian",$arrAllParents);
			$arrAllParents=[];
		}
	}
	if(count($arrAllSmsRecords)!=0)
	{
		// Create records in sms records
		$resp=$objZoho->insertRecord("SMS_Records",$arrAllSmsRecords);		
		$arrAllSmsRecords=[];
		// Clear sms details in parents
		$resp=$objZoho->bulkUpdateRecords("Parent_Guardian",$arrAllParents);
		$arrAllParents=[];
	}
}
?>