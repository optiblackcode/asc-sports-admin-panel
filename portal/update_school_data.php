<?php
//include "include/common.php";

require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );

$objZoho=new ZOHO_METHODS();
try{	
	if($objZoho->checkTokens()){
		
		//for ($x = 1; $x <= 2; $x++) {
			//echo $x;
			$arrParams = array();
			$arrParams['page'] = 2;
			$criteria="((Season:equals:Spring 2023))";
			$arrParams['criteria']=$criteria;
			$Hired_Coaches=$objZoho->searchRecords("Schools",$arrParams);
			if(count($Hired_Coaches['data'])){
				echo "<pre>";
				print_r($Hired_Coaches);
				foreach($Hired_Coaches['data'] as $HC){
					$id = $HC['id'];
					$arrCoach[]=array("Is_Booked" => true);
					$arrTrigger=["workflow"];
					$respUpdateNewsletter=$objZoho->updateRecord("Schools",$id,$arrCoach,$arrTrigger);
					$arrCoach=[];
					echo "<pre>";
				print_r($respUpdateNewsletter);
					
					
				}
			}
		//}
	}
}			
catch(Exception $e){
	$ResponseData = null;
}		