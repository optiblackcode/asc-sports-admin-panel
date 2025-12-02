<?php
ini_set('max_execution_time', 10800); // 3 Hour
ini_set("memory_limit", "-1");
set_time_limit(0);
include __DIR__."/curl_zoho/class/zoho_methods.class.php";
include __DIR__."/class/sms_log.class.php";
include __DIR__."/send_sms_common.php";

$objSMSLog=new SMS_LOG();
$objZoho=new ZOHO_METHODS();
if($objZoho->checkTokens())
{

	$to='0478883541';
		$source='61488824543';
		$message='Hi testing APi';
		$reference_number='1234';

	$content =  'username='.rawurlencode($username).
				            '&password='.rawurlencode($password).
				            '&to='.rawurlencode($to).
				            '&from='.rawurlencode($source).
				            '&message='.rawurlencode($message).
				            '&ref='.rawurlencode($reference_number);

				            print_r($content);
				            echo "<br>";
		        $smsbroadcast_response = sendSMS($content);

		        print_r($smsbroadcast_response);

}
?>