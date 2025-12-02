<?php

//POSTCODE
$postcode_url = "https://api.postalpincode.in/pincode/";
//$postcode = "1234"; //wrong Data
$postcode = "364002"; //right data
$final_url = $postcode_url.$postcode;
$ch = curl_init();  
curl_setopt($ch,CURLOPT_URL,$final_url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
$output=curl_exec($ch);
$result = json_decode($output);
$result = json_decode(json_encode($result), true);
$res = $result[0];
if($res['Status'] == 'Success'){
	echo "Valid pincode.";
}
else{
	echo "Invalid pincode.";
}
	
//echo '<pre>';
//print_r($result);
//echo '</pre>';


//IFSC CODE
$ifsc_url = "https://ifsc.razorpay.com/";
$ifsc = "1234"; //Wrong Code
$ifsc = "KARB0000001"; //right code
$final_url = $ifsc_url.$ifsc;
$ch2 = curl_init();  
curl_setopt($ch2,CURLOPT_URL,$final_url);
curl_setopt($ch2,CURLOPT_RETURNTRANSFER,true);
$output=curl_exec($ch2);
$httpcode = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
$result = json_decode($output);
$result = json_decode(json_encode($result), true);
if($httpcode == 404){
	echo "Invalid IFSC code.";
}
else{
	//echo '<pre>';
	//print_r($result);
	//echo '</pre>';
	$data = array();
	$data['BankName'] = $result['BANK'];
	$data['BankIFSC'] = $result['IFSC'];
	$data['BankState'] = $result['STATE'];
	$data['BankCity'] = $result['CITY'];
	$data['BankCode'] = $result['BANKCODE'];
	$data['BankBranch'] = $result['BRANCH'];
	echo '<pre>';
	print_r($data);
	echo '</pre>';
	
	
}

?>