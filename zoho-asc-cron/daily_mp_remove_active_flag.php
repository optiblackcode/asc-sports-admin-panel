<?php
require_once("common.php");
$dateTime=date("Y-m-d H:i:s");
//********************* Enter Cron Log *************************
$objCronLogs=new CRON_LOGS();
$arrCronLog=[];
$arrCronLog['cron_name']="Daily Membership Purchase Remove Active Flag";
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
			$json_modules=$objZoho->updateRecord("WF_Triggers","3108913000018055424",$arrRecords,$arrTrigger);

			//echo "<pre>";
			//print_r($json_modules);
		}
		else
		{
			echo "Issue with tokens.";
		}
	}
	catch(Exception $e)
	{
		$crmLog.="Exception : ".$e->getMessage().", ";
		$success=false;
	}
}

?>