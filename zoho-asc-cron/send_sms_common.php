<?php
$username = 'scottmtaylor1';
$password = '8arfGuZRlRb7';

// Function to send sms
function sendSMS($content) 
{
	//return "BAD:0400abc111:Invalid Number";
	// $r=trim("OK:61406000927:545184571");
	// return $r;
    $ch = curl_init('https://api.smsbroadcast.com.au/api-adv.php');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec ($ch);
    $output=trim($output);
    $status_code2 = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close ($ch);
    return $output;    
}

function checkMobileNumber($mobile)
{
	$flag=true;
	$mobile=trim($mobile);
	$mobile=preg_replace('/[^0-9]/','', $mobile);
	$length=strlen($mobile);
	
	if($length==10)
	{
	}
	else if($length==9)
	{
		$mobile="0".$mobile;
	}
	else if($length>10)
	{
		$mobile=preg_replace ( "/^61/","",$mobile);
		if(strlen($mobile)==10)
		{

		}
		else if(strlen($mobile)==9)
		{
			$mobile="0".$mobile;
		}
		else 
		{
			$flag=false;
		}
	}
	else if($length<9)
	{
		$flag=false;
	}

	if($flag)
	{
		$firstPart=substr($mobile,0,4);
		$secondPart=substr($mobile,4,3);
		$thirdPart=substr($mobile,7,3);
		$mobile=$firstPart." ".$secondPart." ".$thirdPart;
		$secondDigit=substr($mobile,1,1);
		if($secondDigit!=4)
		{
			$flag=false;
		}

	}
	else
	{
		$flag=false;
	}
	return $mobile;
}
?>