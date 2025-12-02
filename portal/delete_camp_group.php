<?php
include "include/common.php";
require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );


$host="localhost";
$username="RBUH9jPnna";
$password="BYdxh5JIu!";
$db="asc_datastudio_reportingnew";
$con = mysqli_connect("localhost",$username,$password,$db);
$date = date("Y-m-d H:i:s");
$id = $_GET['id'];
$did = $_GET['id'];
$qrySel="SELECT * FROM `camp_group` WHERE `ID` = '$id'";
$result = mysqli_query($con, $qrySel);
$data = mysqli_fetch_assoc($result);

$raw = json_decode($data['data'],true);


$venue = $raw['vanue'];
$camps = $raw['camps'];
$bulkcamp = $raw['bulkcamp'];
$tal = $raw['tal'];
$cos = $raw['cos'];
$vid = $raw['vid'];
$cd = $raw['cd'];
$fa = $raw['fa'];
$coach = $raw['coach'];
$inc = $raw['inc'];


$objZoho=new ZOHO_METHODS();
try{	
	if($objZoho->checkTokens()){
		foreach($cos as $c){
			$zohoid =  $c['coach'];
			$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
			if(count($arrBookingResults['data'])){
				$Email = $arrBookingResults['data'][0]['Email'];
				$criteria="((Email:equals:".$Email."))";
				$arrParams['criteria']=$criteria;
				$Email = $arrBookingResults['data'][0]['Email'];
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['id'];
					foreach($bulkcamp as $bc){
						$criteria="((Camps:equals:".$bc.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
				}		
			}
		}
		foreach($vid as $c){
			$zohoid =  $c['coach'];
			$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
			if(count($arrBookingResults['data'])){
				$Email = $arrBookingResults['data'][0]['Email'];
				$criteria="((Email:equals:".$Email."))";
				$arrParams['criteria']=$criteria;
				$Email = $arrBookingResults['data'][0]['Email'];
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['id'];
					foreach($bulkcamp as $bc){
						$criteria="((Camps:equals:".$bc.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
				}		
			}
		}
		foreach($tal as $t){
			$zohoid =  $t['coach'];
			$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
			if(count($arrBookingResults['data'])){
				$Email = $arrBookingResults['data'][0]['Email'];
				$criteria="((Email:equals:".$Email."))";
				$arrParams['criteria']=$criteria;
				$Email = $arrBookingResults['data'][0]['Email'];
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['id'];
					foreach($bulkcamp as $bc){
						$criteria="((Camps:equals:".$bc.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
				}		
			}
		}
		foreach($cd as $c){
			$zohoid =  $c['coach'];
			$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
			if(count($arrBookingResults['data'])){
				$Email = $arrBookingResults['data'][0]['Email'];
				$criteria="((Email:equals:".$Email."))";
				$arrParams['criteria']=$criteria;
				$Email = $arrBookingResults['data'][0]['Email'];
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['id'];
					foreach($bulkcamp as $bc){
						$criteria="((Camps:equals:".$bc.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
				}		
			}
		}
		foreach($fa as $c){
			$zohoid =  $c['coach'];
			$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
			if(count($arrBookingResults['data'])){
				$Email = $arrBookingResults['data'][0]['Email'];
				$criteria="((Email:equals:".$Email."))";
				$arrParams['criteria']=$criteria;
				$Email = $arrBookingResults['data'][0]['Email'];
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['id'];
					foreach($bulkcamp as $bc){
						$criteria="((Camps:equals:".$bc.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
				}		
			}
		}
		foreach($coach as $c){
			$zohoid =  $c['coach'];
			$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
			if(count($arrBookingResults['data'])){
				$Email = $arrBookingResults['data'][0]['Email'];
				$criteria="((Email:equals:".$Email."))";
				$arrParams['criteria']=$criteria;
				$Email = $arrBookingResults['data'][0]['Email'];
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['id'];
					$criteria="((Camps:equals:".$camps.") and (Hired_Coaches:equals:".$ContactID."))";
					$arrParams['criteria']=$criteria;
					$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
					$id = $respSearch['data'][0]['id'];
					$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
					
					$criteria="((Camp:equals:".$camps.") and (Coach:equals:".$ContactID."))";
					$arrParams['criteria']=$criteria;
					$respSearch=$objZoho->searchRecords("Coach_Attendance",$arrParams);
					if(count($respSearch['data'])){
						foreach($respSearch['data'] as $sd){
							$id = $sd['id'];
							$objZoho->deleteRecord("Coach_Attendance",$id); //delete recored
						}
					}
					
				}		
			}
		}
		
		for ($x = 0; $x <= $inc; $x++) {
									
			$name = $raw['grp'.$x];	
			$criteria="((Group_Name:equals:".$name.") and (Camp_ID:equals:".$camps."))";
			$arrParams['criteria']=$criteria;
			$respSearch=$objZoho->searchRecords("Participant_Group",$arrParams);
			if(count($respSearch['data'])){
				foreach($respSearch['data'] as $sd){
					$id = $sd['id'];
					$objZoho->deleteRecord("Participant_Group",$id); //delete recored
				}
			}
			
		}
		$qrySel="DELETE FROM `camp_group` WHERE `ID` = '$did'";
		$result = mysqli_query($con, $qrySel);
	}
}			
catch(Exception $e){
	$ResponseData = null;
}


echo "<script type='text/javascript'> document.location = 'list_camp_group.php?success=1'; </script>";






?>