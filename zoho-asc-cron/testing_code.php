<?php
include __DIR__."/curl_zoho/class/zoho_methods.class.php";
$objZoho=new ZOHO_METHODS();
	if($objZoho->checkTokens())
{
	echo "if";
/*$moduleIns=ZCRMRestClient::getInstance()->getModuleInstance("Leads");  //To get module instance
$response=$moduleIns->searchRecordsByCriteria("("((Last_Name:starts_with:B) and (email:equals:burns.mary@xyz.com))")",1,200);  //To get module records that match the criteria
$records=$response->getData(); */ //To get response data



$criteria="((Last_Name:starts_with:B) and (Email:equals:burns.mary@xyz.com))";
print_r($criteria);
$arrParams=[];
$arrParams['criteria']=$criteria;

$respSearchContacts=$objZoho->searchRecords("Leads",$arrParams);

print_r($respSearchContacts);
}
?>