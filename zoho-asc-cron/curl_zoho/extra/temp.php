<?php
require "class/zoho.class.php";
$objZoho=new ZOHO_METHODS();
if($objZoho->checkTokens())
{
	$arr=[];
	$response=$objZoho->getRecords("Suburb",$arr);
	echo "<pre>";
	print_r($response);
}
else
{
	echo "Not working";
}

?>