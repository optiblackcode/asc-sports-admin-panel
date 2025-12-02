<?php
//echo "if";
include __DIR__."/curl_zoho/class/zoho_methods.class.php";
include __DIR__."/class/sms_log.class.php";
include __DIR__."/send_sms_common.php";
$objSMSLog=new SMS_LOG();
$objZoho=new ZOHO_METHODS();
if($objZoho->checkTokens())
{

	$rsltProcessesSms=$objSMSLog->GetSMSLOGManaully();
	while($rowProcesses=$rsltProcessesSms->fetch_assoc())
	{
		
		$parent_contact_id = $rowProcesses['contact_id'];
		

		/*$fetch_zoho = $objZoho->getRecordById("Parent_Guardian",$parent_contact_id);
		echo "<pre>";
		print_r($fetch_zoho);*/

		//Manual_Flag_Do_Not_Send
		$arrUpdate['Manual_Flag_Do_Not_Send']= 1;
		
		$arrUpdateZoho=[];
		$arrUpdateZoho[]=$arrUpdate;

		
		$resp=$objZoho->updateRecord("Parent_Guardian",$parent_contact_id,$arrUpdateZoho);
		
		$id_update = $resp['data'][0]['details']['id'];
		$status_message = $resp['data'][0]['message'];
		$status =  $resp['data'][0]['status'];

		echo "Database Id: ".$parent_contact_id.'<br>'."Update Id ZOHO: ". $id_update.'<br>'."Message: ". $status_message.'<br>'."Status: ". $status.'<br>';


	} //end of while loop of query

} //  end of check tokens loop

?>