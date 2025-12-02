<?php
include "class/camps.class.php";
include "class/schools_booked.class.php";
include "class/camp_association.class.php";

$objZohoCamps=new CAMPS();
$objZohoSB=new SCHOOLS_BOOKED();
$objZohoSBCampAss=new CAMP_ASSOCIATION();

$requestInProcess=false;
$rsltCamps=$objZohoCamps->getAll();
$rsltSB=$objZohoSB->getAll();
$rsltSBCamps=$objZohoSBCampAss->getAll();

if($rsltCamps)
{
	if($rsltCamps->num_rows>0)
	{
		$requestInProcess=true;
	}
}
else
{
	$requestInProcess=true;
}

if($rsltSB)
{
	if($rsltSB->num_rows>0)
	{
		$requestInProcess=true;
	}
}
else
{
	$requestInProcess=true;
}

if($rsltSBCamps)
{
	if($rsltSBCamps->num_rows>0)
	{
		$requestInProcess=true;
	}
}
else
{
	$requestInProcess=true;
}


$message="";
if($requestInProcess)
{

	$message="Request already exists.";
}
else
{
	$url="http://31.220.55.121/zoho-asc-cron/associate_camps_actual.php";
	$timeout="5";
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	$content = curl_exec( $ch );
	$response = curl_getinfo( $ch );
	curl_close ( $ch );

	$message="Request sent successfully.";
}

echo $message;

request_log($message);
function request_log($log)
{
	$log=date("Y-m-d H:i:s")."\t".$log;
	$log.="\n**********************************************************";
	$file=fopen("school_booked_request_log.log", "a+");
	fwrite($file, $log);
	fclose($log);
}
?>