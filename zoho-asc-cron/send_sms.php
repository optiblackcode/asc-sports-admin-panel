<?php
$module="";
if(isset($_REQUEST['module']))
{
	$module=$_REQUEST['module'];
}

if($module!="parent" && $module!="contact" && $module!="prospect" && $module!="hiredcoaches")
{
	echo "Something went wrong.";
}
else
{
	include __DIR__."/class/sms_log.class.php";
	$objSMSLog=new SMS_LOG();
	$rsltCheckPendingRequests=$objSMSLog->checkPendingRequests();
	if($rsltCheckPendingRequests)
	{
		if($rsltCheckPendingRequests->num_rows==0)
		{
			if($module=="parent")
			{
				$url="http://31.220.55.121/zoho-asc-cron/send_sms_parent.php";
			}
			else if($module=="prospect")
			{
				$url="http://31.220.55.121/zoho-asc-cron/send_sms_prospect.php";
			}
			else if($module=="hiredcoaches")
			{
				$url="http://31.220.55.121/zoho-asc-cron/send_sms_hiredcoaches.php";
			}
			else
			{
				$url="http://31.220.55.121/zoho-asc-cron/send_sms_contact.php";
			}
			
			$timeout="5";
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
			$content = curl_exec( $ch );
			$response = curl_getinfo( $ch );
			curl_close ( $ch );
			
			echo "Request sent successfully.";
		}
		else 
		{
			echo "Sms request already exists, please try after sometime.";
		}
	}
	else
	{
		echo "Something went wrong.";
	}
}
?>