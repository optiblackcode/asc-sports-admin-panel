<?php
//echo "if";
include __DIR__."/curl_zoho/class/zoho_methods.class.php";
include __DIR__."/class/sms_log.class.php";
include __DIR__."/send_sms_common.php";
$objSMSLog=new SMS_LOG();
$objZoho=new ZOHO_METHODS();
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