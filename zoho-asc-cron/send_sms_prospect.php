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
	// ******************* Get all sms to send and insert in database ********************
	$moreRecord=true;
	$maxCount=40;
	$i=1;
	while($i<=$maxCount && $moreRecord)
	{
		$arrParams=[];
		$arrParams['cvid']="3108913000019553057";
		$arrParams['page']=$i;
		$arrParams['per_page']=200; 
		$respSearchContacts=$objZoho->getRecords("Prospects",$arrParams);
		if(isset($respSearchContacts['data']))
		{
			if(count($respSearchContacts['data'])>0)
			{
				$arrBatchSms=[];
				foreach ($respSearchContacts['data'] as $key => $arrProspect) 
				{
					$arrSms=[];	
					$arrSms['to_number']=str_replace(" ","",$arrProspect['Primary_Phone']);
					$arrSms['from_number']="61488824543";
					$arrSms['message']=$arrProspect['SMS_Text'];
					$arrSms['reference']="";
					$arrSms['contact_type']="prospect";
					$arrSms['contact_id']=$arrProspect['id'];
					//$arrSms['sms_opt_out']=$arrProspect['SMS_Opt_Out'];
					$arrSms['schedule_time']=$arrProspect['Schedule_SMS'];
					$arrSms['processing_status']="QUEUE";
					$arrBatchSms[]=$arrSms;
				}
				// Insert records in database
				if(!$objSMSLog->insert($arrBatchSms))
				{
					die("Failed to insert in database");
				}
				// ******************************
				// Check if more records exirst
				if(isset($respSearchContacts['info']))
				{
					if($respSearchContacts['info']['more_records']!=1)
					{
						$moreRecord=false;		
					}
				}
				else
				{
					$moreRecord=false;		
				}
			}
			else
			{
				$moreRecord=false;		
			}
		}
		else
		{
			$moreRecord=false;
		}
		$i++;
	}
	// Get all sms from database and call api for sms broadcast
	$rsltSmsLog=$objSMSLog->getSMSFromQueue('prospect');
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
		if($objSMSLog->updateStatuses($id,$status,$processingStatus,$reference,$delayMin))
		{

		}
		else
		{
			echo "Error in updating record.";
		}
	}
}
if($objZoho->checkTokens())
{
	// Get processed sms from databases
	$recordCount=0;
	$arrAllSmsRecords=[];
	$arrAllProspects=[];
	$rsltProcessesSms=$objSMSLog->getSMSFromProcessed('prospect');
	while($rowProcesses=$rsltProcessesSms->fetch_assoc())
	{
		// Update status to done
		$objSMSLog->updateProcessingStatus($rowProcesses['id'],"DONE");

		// ***************************************
		$arrSmsRecord=[];
		$arrSmsRecord['Message_Content']=$rowProcesses['message'];
		$arrSmsRecord['To_Number']=checkMobileNumber($rowProcesses['to_number']);
		$arrSmsRecord['Type']='Sent';
		$arrSmsRecord['Prospect_Name']=$rowProcesses['contact_id'];
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
		$arrSingleProspect=[];
		$arrSingleProspect['id']=$rowProcesses['contact_id'];
		$arrSingleProspect['SMS_Text']='';
		$arrSingleProspect['Send_Now']=false;
		$arrSingleProspect['Schedule_SMS']='';
		$arrSingleProspect['SMS_Text']='';
		$arrAllProspects[]=$arrSingleProspect;
		// **********************************************
		$recordCount++;
		if($recordCount>=95)
		{
			// Create records in sms records
			$recordCount=0;
			$resp=$objZoho->insertRecord("SMS_Records",$arrAllSmsRecords);
			$arrAllSmsRecords=[];
			// Clear sms details in prospects
			$resp=$objZoho->bulkUpdateRecords("Prospects",$arrAllProspects);
			$arrAllProspects=[];
		}
	}
	if(count($arrAllSmsRecords)!=0)
	{
		// Create records in sms records
		$resp=$objZoho->insertRecord("SMS_Records",$arrAllSmsRecords);		
		$arrAllSmsRecords=[];
		// Clear sms details in prospects
		$resp=$objZoho->bulkUpdateRecords("Prospects",$arrAllProspects);
		$arrAllProspects=[];
	}
}
?>