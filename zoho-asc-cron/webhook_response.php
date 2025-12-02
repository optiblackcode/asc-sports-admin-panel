<?php
ini_set('max_execution_time', 10800); // 3 hours
ini_set("memory_limit", "-1");
set_time_limit(0);
require_once("common.php");
$arrCronLog=['cron_id'=>'0',
				'response'=>'',
				'records_affected'=>'',
				'errors'=>'',
				'error_log'=>''
			];

if(isset($_REQUEST['cron_id']))
{
	$arrCronLog['cron_id']=$_REQUEST['cron_id'];
}
else
{
	exit();
}
if(isset($_REQUEST['response']))
{
	$arrCronLog['response']=$_REQUEST['response'];
}
if(isset($_REQUEST['records_affected']))
{
	$arrCronLog['records_affected']=$_REQUEST['records_affected'];
}
if(isset($_REQUEST['errors']))
{
	$arrCronLog['errors']=$_REQUEST['errors'];
}
if(isset($_REQUEST['error_log']))
{
	$arrCronLog['error_log']=$_REQUEST['error_log'];
}
//********************* Update Cron Log *************************
$objCronLogs=new CRON_LOGS();
$response=$objCronLogs->update($arrCronLog);

echo "Working";
?>