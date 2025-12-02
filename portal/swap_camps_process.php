<?php
require_once __DIR__."/../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php";
$orederID =  $_POST['order'];
$camp = $_POST['camp'];
$child = $_POST['child'];
$Name = $_POST['Name'];
$old_string = $_POST['old_string'];
$oldids = $_POST['oldids'];
$chnaged_ids = array();
$k = 0;
foreach($oldids as $oi){
	
	if($oi != $camp[$k]){
		$chnaged_ids[] = $camp[$k];	
	}
	$k++;
}



$post = [
    'camp' => $camp,
    'child' => $child,
    'Name' => $Name,
];
$fields_string = http_build_query($post);
$ch = curl_init('https://shop.australiansportscamps.com.au/wp-json/newasc/v2/child_details');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
$response = curl_exec($ch);
curl_close($ch);
$response = json_decode($response, true);
$ResponseCode = $response['ResponseCode'];
$ResponseData = $response['ResponseData'];

if($ResponseCode === 200){
	$objZoho=new ZOHO_METHODS();
	try{
		
		$skus = array();	
		if($objZoho->checkTokens()){
			$new_string = array();
			foreach($ResponseData as $Data){
				$new_string[] = $Data['ChildName']." : ".$Data['Camp'];
				$ChildID = $Data['ChildID'];
				$ChildName = $Data['ChildName'];
				$CampSKU = $Data['CampSKU'];
				$skus[] = $Data['CampSKU'];
				$Camp = $Data['Camp'];
				$CampSport = $Data['CampSport'];
				$CampZOHOID = $Data['CampZOHOID'];
				$CampID = $Data['CampID'];
				$criteria="((Participant_Id_Website:equals:".$ChildID."))";
				$arrParams['criteria']=$criteria;
				$respChild=$objZoho->searchRecords("Participant",$arrParams);
				if(count($respChild['data'])){
					$child_zoho_id = $respChild['data'][0]['id'];
					$arrChild['Camp_Name']=$CampZOHOID;
					$arrChild['SKU']=$CampSKU;
					$arrChild['Camp_Name_Wordpress_A']=$Camp;
					$arrChild['Sports_A']=$CampSport;
					$arrUpdateChild=[];
					$arrUpdateChild[]=$arrChild;
					$arrTrigger=["workflow"];
					$respUpdateNewsletter=$objZoho->updateRecord("Participant",$child_zoho_id,$arrUpdateChild,$arrTrigger);
				}
			}
			$criteria="((Order_Id:equals:".$orederID."))";
			$arrParams['criteria']=$criteria;
			$respBook=$objZoho->searchRecords("Bookings",$arrParams);
			if(count($respBook['data'])){
				$Book_zoho_id = $respBook['data'][0]['id'];
				$finalsku = implode(",",$skus);
				$arrBooking['SKU']=$finalsku;
				$arrBooking['Sports']=$CampSport;
				$arrUpdateBooking=[];
				$arrUpdateBooking[]=$arrBooking;
				$arrTrigger=["workflow"];
				$respUpdateNews=$objZoho->updateRecord("Bookings",$Book_zoho_id,$arrUpdateBooking,$arrTrigger);
			}	
			/*Order Meta*/
			$str = 'OLD DATA :- |';
			foreach($old_string as $os){
			$str .= $os.' |';
			}
			$str .= 'NEW DATA :- |';
			foreach($new_string as $ns){
				$str .= $ns.' |';
			}
			$post = [
				'OrderID' => $orederID,
				'str' => $str,
				'chnaged_ids' => implode(",",$chnaged_ids),
				
			];
			$fields_string = http_build_query($post);
			$ch = curl_init('https://shop.australiansportscamps.com.au/wp-json/newasc/v2/update_order');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
			$response = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($response, true);
			
			
			
		}
		else{
			$crmLog.=", Token-Error";
			$success=false;
		}
	}
	catch(Exception $e){
	  $crmLog.="Exception : ".$e->getMessage().", ";
	  $success=false;
	}
	
	echo '<script type="text/javascript">
           window.location = "http://31.220.55.121/portal/swap_camps.php"
      </script>';
	
	
}
?>