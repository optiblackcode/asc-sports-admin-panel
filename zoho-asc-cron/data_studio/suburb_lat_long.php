<?php
require_once __DIR__."/class/execution_logs.class.php";
$objExecutionLog=new EXECUTION_LOGS();
$execusitonLogId=$objExecutionLog->log_id;

require_once __DIR__."/class/zoho_suburbs.class.php";
$objSuburb=new ZOHO_SUBURBS();

$rsltAllSuburbs=$objSuburb->getAllSuburbs();
if($rsltAllSuburbs)
{
	if($rowSuburb=$rsltAllSuburbs->fetch_assoc())
	{
		echo "<pre>";
		print_r($rowSuburb);
		die();
	}
}
else
{
	echo "Error in getting all records.";
}