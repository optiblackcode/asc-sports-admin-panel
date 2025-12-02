<?php
require_once("common.php");
$dateTime=date("Y-m-d H:i:s");
//********************* Enter Cron Log *************************
$objCronLogs=new CRON_LOGS();
$arrCronLog=[];
$arrCronLog['cron_name']="Daily Venue Update";
$cronId=$objCronLogs->insert($arrCronLog);
if($cronId)
{
	//********************* Zoho Api *******************************
	$objZoho=new ZOHO_METHODS();
	try
	{
		if($objZoho->checkTokens())
		{
			$random=rand(1,100);

			$arrData=[];
			$arrData['Trigger_Field']=(string)$random;
			$arrData['Cron_Id']=(string)$cronId;

			$arrRecords[]=$arrData;
			$arrTrigger=["workflow"];
			$json_modules=$objZoho->updateRecord("WF_Triggers","3108913000000385082",$arrRecords,$arrTrigger);

			//********************** Send mail when cron executed ***************
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= "From: info@qltech.com.au";
			$mailbody="<h3>Daily Camp Update Executed</h3><h5>Date Time (Victoria) : ".$dateTime."</h5>";
			mail("raj.gautam@qltech.com.au","Cron : Daily Camp Update",$mailbody,$headers);
			mail("sunny@qltech.com.au","Cron : Daily Camp Update",$mailbody,$headers);
			mail("sumer@qltech.com.au","Cron : Daily Camp Update",$mailbody,$headers);
			mail("manish@qltech.com.au","Cron : Daily Camp Update",$mailbody,$headers);
			mail("chintan@qltech.com.au","Cron : Daily Camp Update",$mailbody,$headers);
			

			// echo "<pre>";
			// print_r($json_modules);
		}
	}
	catch(Exception $e)
	{
		$crmLog.="Exception : ".$e->getMessage().", ";
		$success=false;
	}
}

?>