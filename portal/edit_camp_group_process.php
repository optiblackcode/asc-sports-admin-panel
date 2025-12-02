<?php

include "include/common.php";
require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );

$coachhired = $_POST['coachhired'];
$id = $_POST['id'];
$host="localhost";
$username="RBUH9jPnna";
$password="BYdxh5JIu!";
$db="asc_datastudio_reportingnew";
$con = mysqli_connect("localhost",$username,$password,$db);
$date = date("Y-m-d H:i:s");

$qrySel="SELECT * FROM `camp_group` WHERE `ID` = '$id'";
$result = mysqli_query($con, $qrySel);
$data = mysqli_fetch_assoc($result);
$str = $data['CC'];
$raw = json_decode($data['data'],true);
$venue = $raw['vanue'];
$camps = $raw['camps'];
$bulkcamp = $raw['bulkcamp'];
$cos = $raw['cos'];
$vid = $raw['vid'];
$tal = $raw['tal'];
$cd = $raw['cd'];
$fa = $raw['fa'];
$coach = $raw['coach'];
$inc = $raw['inc'];


$objZoho=new ZOHO_METHODS();
try{	
	if($objZoho->checkTokens()){
		
		
		if($str != ""){
			$arr = json_decode($str,true);
			if(is_array($arr)){
				foreach($arr as $a){
					$objZoho->deleteRecord("Hired_Coaches_X_Camps",$a);
				}
			}
		}
		
		
		/*Delete All process*/
		
		
		foreach($cos as $c){
			 $zohoid =  $c['coach'];
			$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
			if(count($arrBookingResults['data'])){
				 $Email = $arrBookingResults['data'][0]['Email'];
				$criteria="((Email:equals:".$Email."))";
				$arrParams['criteria']=$criteria;
				
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['Name'];
					foreach($bulkcamp as $bc){
						$arrcamp=$objZoho->getRecordById("Camps",$bc);
						if(count($arrcamp['data'])){
							$Camp = $arrcamp['data'][0];
							$Names = $Camp['Name'];
						}
						$criteria="((Camps:equals:".$Names.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
					$objZoho->deleteRecord("Hired_Coaches",$ContactID); //delete recored
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
				
				$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
				
				if(count($respSearchNewsletter['data'])){
					$ContactID=$respSearchNewsletter['data'][0]['Name'];
					foreach($bulkcamp as $bc){
						$arrcamp=$objZoho->getRecordById("Camps",$bc);
						if(count($arrcamp['data'])){
							$Camp = $arrcamp['data'][0];
							$Names = $Camp['Name'];
						}
						$criteria="((Camps:equals:".$Names.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
					$objZoho->deleteRecord("Hired_Coaches",$ContactID); //delete recored
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
					$ContactID=$respSearchNewsletter['data'][0]['Name'];
					foreach($bulkcamp as $bc){
						$arrcamp=$objZoho->getRecordById("Camps",$bc);
						if(count($arrcamp['data'])){
							$Camp = $arrcamp['data'][0];
							$Names = $Camp['Name'];
						}
						$criteria="((Camps:equals:".$Names.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
					$objZoho->deleteRecord("Hired_Coaches",$ContactID); //delete recored
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
					$ContactID=$respSearchNewsletter['data'][0]['Name'];
					foreach($bulkcamp as $bc){
						$arrcamp=$objZoho->getRecordById("Camps",$bc);
						if(count($arrcamp['data'])){
							$Camp = $arrcamp['data'][0];
							$Names = $Camp['Name'];
						}
						$criteria="((Camps:equals:".$Names.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
					$objZoho->deleteRecord("Hired_Coaches",$ContactID); //delete recored
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
					$ContactID=$respSearchNewsletter['data'][0]['Name'];
					foreach($bulkcamp as $bc){
						$arrcamp=$objZoho->getRecordById("Camps",$bc);
						if(count($arrcamp['data'])){
							$Camp = $arrcamp['data'][0];
							$Names = $Camp['Name'];
						}
						$criteria="((Camps:equals:".$Names.") and (Hired_Coaches:equals:".$ContactID."))";
						$arrParams['criteria']=$criteria;
						$respSearch=$objZoho->searchRecords("Hired_Coaches_X_Camps",$arrParams);
						$id = $respSearch['data'][0]['id'];
						$objZoho->deleteRecord("Hired_Coaches_X_Camps",$id); //delete recored
						
					}
					$objZoho->deleteRecord("Hired_Coaches",$ContactID); //delete recored
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
					$ContactID=$respSearchNewsletter['data'][0]['Name'];
					$arrcamp=$objZoho->getRecordById("Camps",$camps);
						if(count($arrcamp['data'])){
							$Camp = $arrcamp['data'][0];
							$Names = $Camp['Name'];
						}
					$criteria="((Camps:equals:".$Names.") and (Hired_Coaches:equals:".$ContactID."))";
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
					$objZoho->deleteRecord("Hired_Coaches",$ContactID); //delete recored
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
					$idss = $sd['id'];
					$objZoho->deleteRecord("Participant_Group",$idss); //delete recored
				}
			}
			
		}
		
		/*Delete All process*/
	
	
	/*Insert again process*/
	$id_camp = $_POST['id'];
	$coaches = $_POST['coach'];
	$fa = $_POST['fa'];
	$cd = $_POST['cd'];
	$cos = $_POST['cos'];
	$vid = $_POST['vid'];
	$tal = $_POST['tal'];
	$bulkcamp = $_POST['bulkcamp'];
	$camps = $_POST['camps'];
	$grp = $_POST['inc'];
	$Hired_Coaches_X_Camps =array();
	$date = date("Y-m-d H:i:s");
	 $data = json_encode($_POST);
	$host="localhost";
	$username="RBUH9jPnna";
	$password="BYdxh5JIu!";
	$db="asc_datastudio_reportingnew";
	$con = mysqli_connect("localhost",$username,$password,$db);
	  $qrySel="UPDATE `camp_group` SET `data`='$data',`date`='$date' WHERE `ID` = '$id_camp'";
	$result = mysqli_query($con, $qrySel);
	
	
	
	
	
	
	foreach($tal as $t){
				$zohoid = $t['coach'];
				$coachhired = $t['coachhired'];
				$total = $t['total'];
				$day1avail = $t['day1avail'];
				$day1price = $t['day1price'];
				$day2avail = $t['day2avail'];
				$day2price = $t['day2price'];
				$day3avail = $t['day3avail'];
				$day3price = $t['day3price'];
				$Admin_Notes = $t['notes'];
				
				if($coachhired == 1){
					$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
					if(count($arrBookingResults['data'])){
						$idd = $arrBookingResults['data'][0]['id'];
						$Name = $arrBookingResults['data'][0]['Name'];
						$Email = $arrBookingResults['data'][0]['Email'];
						//$Role = $arrBookingResults['data'][0]['Role'];
						$Role = array("Talent");
						//array_push($Role,"Talent");
							$arrZohoCoach=[
								"Coach_Name"=>$idd,
								"Coach_Email"=>$Email,
								"Email"=>$Email,
								"Total_Cost"=>$total,
								"Default_Hire_Price"=>$total,
								"Total_Cost_Inc_GST_A"=>$total,
								"Overall_Cost"=>$total,
								"Cost_Type"=>"Overall",
								"Role"=>$Role,
								"Admin_Notes"=>$Admin_Notes,
							];
							$arrInsertContact[]=$arrZohoCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches",$arrInsertContact,$arrTrigger);
							$ContactID = $respInsertContact['data'][0]['details']['id'];
						
						
						foreach($bulkcamp as $bc){
							$arrHCoach = array();
							$arrInsertHContact = array();
							$arrHCoach=[
								"Camps"=>$bc,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,	
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						}
					}
				}
				$arrInsertHContact = []; 
				$arrHCoach = []; 
				$arrZohoCoach = []; 
				$arrInsertContact = []; 
				$arrBookingResults = "";
			}
			
			
			foreach($fa as $f){
				$zohoid = $f['coach'];
				$coachhired = $f['coachhired'];
				$total = $f['total'];
				$day1avail = $f['day1avail'];
				$day1price = $f['day1price'];
				$day2avail = $f['day2avail'];
				$day2price = $f['day2price'];
				$day3avail = $f['day3avail'];
				$day3price = $f['day3price'];
				$Admin_Notes = $f['notes'];
				
				if($coachhired == 1){
					$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
					if(count($arrBookingResults['data'])){
						$idd = $arrBookingResults['data'][0]['id'];
						$Name = $arrBookingResults['data'][0]['Name'];
						$Email = $arrBookingResults['data'][0]['Email'];
						//$Role = $arrBookingResults['data'][0]['Role'];
						$Role = array("First Aid");
						//array_push($Role,"Talent");
							$arrZohoCoach=[
								"Coach_Name"=>$idd,
								"Coach_Email"=>$Email,
								"Email"=>$Email,
								"Total_Cost"=>$total,
								"Default_Hire_Price"=>$total,
								"Total_Cost_Inc_GST_A"=>$total,
								"Cost_Type"=>"Overall",
								"Overall_Cost"=>$total,
								"Role"=>$Role,
								"Admin_Notes"=>$Admin_Notes,
							];
							$arrInsertContact[]=$arrZohoCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches",$arrInsertContact,$arrTrigger);
							$ContactID = $respInsertContact['data'][0]['details']['id'];
							$arrHCoach=[
								"Camps"=>$camps,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,
								
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						
						/*foreach($bulkcamp as $bc){
							$arrHCoach = array();
							$arrInsertHContact = array();
							$arrHCoach=[
								"Camps"=>$bc,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,	
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						}*/
					}
				}
				$arrInsertHContact = []; 
				$arrHCoach = []; 
				$arrZohoCoach = []; 
				$arrInsertContact = []; 
				$arrBookingResults = "";
			}
			
			foreach($cd as $c){
				$zohoid = $c['coach'];
				$coachhired = $c['coachhired'];
				$total = $c['total'];
				$day1avail = $c['day1avail'];
				$day1price = $c['day1price'];
				$day2avail = $c['day2avail'];
				$day2price = $c['day2price'];
				$day3avail = $c['day3avail'];
				$day3price = $c['day3price'];
				$Admin_Notes = $c['notes'];
				
				if($coachhired == 1){
					$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
					if(count($arrBookingResults['data'])){
						$idd = $arrBookingResults['data'][0]['id'];
						$Name = $arrBookingResults['data'][0]['Name'];
						$Email = $arrBookingResults['data'][0]['Email'];
						//$Role = $arrBookingResults['data'][0]['Role'];
						$Role = array("Coaching Director");
						//array_push($Role,"Talent");
							$arrZohoCoach=[
								"Coach_Name"=>$idd,
								"Coach_Email"=>$Email,
								"Email"=>$Email,
								"Total_Cost"=>$total,
								"Default_Hire_Price"=>$total,
								"Total_Cost_Inc_GST_A"=>$total,
								"Cost_Type"=>"Overall",
								"Overall_Cost"=>$total,
								"Role"=>$Role,
								"Admin_Notes"=>$Admin_Notes,
							];
							$arrInsertContact[]=$arrZohoCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches",$arrInsertContact,$arrTrigger);
							$ContactID = $respInsertContact['data'][0]['details']['id'];
							
							$arrHCoach=[
								"Camps"=>$camps,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,
								
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						
						/*foreach($bulkcamp as $bc){
							$arrHCoach = array();
							$arrInsertHContact = array();
							$arrHCoach=[
								"Camps"=>$bc,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,	
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						}*/
					}
				}
				$arrInsertHContact = []; 
				$arrHCoach = []; 
				$arrZohoCoach = []; 
				$arrInsertContact = []; 
				$arrBookingResults = "";
			}
			foreach($vid as $co){
				$zohoid = $co['coach'];
				$coachhired = $co['coachhired'];
				$total = $co['total'];
				$day1avail = $co['day1avail'];
				$day1price = $co['day1price'];
				$day2avail = $co['day2avail'];
				$day2price = $co['day2price'];
				$day3avail = $co['day3avail'];
				$day3price = $co['day3price'];
				$Admin_Notes = $co['notes'];
				
				if($coachhired == 1){
					$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
					if(count($arrBookingResults['data'])){
						$idd = $arrBookingResults['data'][0]['id'];
						$Name = $arrBookingResults['data'][0]['Name'];
						$Email = $arrBookingResults['data'][0]['Email'];
						//$Role = $arrBookingResults['data'][0]['Role'];
						$Role = array("Video");
						//array_push($Role,"Talent");
							$arrZohoCoach=[
								"Coach_Name"=>$idd,
								"Coach_Email"=>$Email,
								"Email"=>$Email,
								"Total_Cost"=>$total,
								"Default_Hire_Price"=>$total,
								"Total_Cost_Inc_GST_A"=>$total,
								"Cost_Type"=>"Overall",
								"Overall_Cost"=>$total,
								"Role"=>$Role,
								"Admin_Notes"=>$Admin_Notes,
							];
							$arrInsertContact[]=$arrZohoCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches",$arrInsertContact,$arrTrigger);
							$ContactID = $respInsertContact['data'][0]['details']['id'];
						
						
						foreach($bulkcamp as $bc){
							$arrHCoach = array();
							$arrInsertHContact = array();
							$arrHCoach=[
								"Camps"=>$bc,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,	
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						}
					}
				}
				$arrInsertHContact = []; 
				$arrHCoach = []; 
				$arrZohoCoach = []; 
				$arrInsertContact = []; 
				$arrBookingResults = "";
			}
			foreach($cos as $co){
				$zohoid = $co['coach'];
				$coachhired = $co['coachhired'];
				$total = $co['total'];
				$day1avail = $co['day1avail'];
				$day1price = $co['day1price'];
				$day2avail = $co['day2avail'];
				$day2price = $co['day2price'];
				$day3avail = $co['day3avail'];
				$day3price = $co['day3price'];
				$Admin_Notes = $co['notes'];
				
				if($coachhired == 1){
					$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
					if(count($arrBookingResults['data'])){
						$idd = $arrBookingResults['data'][0]['id'];
						$Name = $arrBookingResults['data'][0]['Name'];
						$Email = $arrBookingResults['data'][0]['Email'];
						//$Role = $arrBookingResults['data'][0]['Role'];
						$Role = array("Chief of Staff");
						//array_push($Role,"Talent");
							$arrZohoCoach=[
								"Coach_Name"=>$idd,
								"Coach_Email"=>$Email,
								"Email"=>$Email,
								"Total_Cost"=>$total,
								"Default_Hire_Price"=>$total,
								"Total_Cost_Inc_GST_A"=>$total,
								"Cost_Type"=>"Overall",
								"Overall_Cost"=>$total,
								"Role"=>$Role,
								"Admin_Notes"=>$Admin_Notes,
							];
							$arrInsertContact[]=$arrZohoCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches",$arrInsertContact,$arrTrigger);
							$ContactID = $respInsertContact['data'][0]['details']['id'];
							$arrHCoach=[
								"Camps"=>$camps,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,
								
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						
						/*foreach($bulkcamp as $bc){
							$arrHCoach = array();
							$arrInsertHContact = array();
							$arrHCoach=[
								"Camps"=>$bc,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,	
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						}*/
					}
				}
				$arrInsertHContact = []; 
				$arrHCoach = []; 
				$arrZohoCoach = []; 
				$arrInsertContact = []; 
				$arrBookingResults = "";
			}
			
			$Att = array();
			$Coaches = array();
			foreach($coaches as $c){
				$zohoid = $c['coach'];
				$coachhired = $c['coachhired'];
				$total = $c['total'];
				$day1avail = $c['day1avail'];
				$day1price = $c['day1price'];
				$day2avail = $c['day2avail'];
				$day2price = $c['day2price'];
				$day3avail = $c['day3avail'];
				$day3price = $c['day3price'];
				$Admin_Notes = $c['notes'];
				if($coachhired == 1){
					
					$arrBookingResults=$objZoho->getRecordById("Coaches",$zohoid);
					if(count($arrBookingResults['data'])){
						$idd = $arrBookingResults['data'][0]['id'];
						$Name = $arrBookingResults['data'][0]['Name'];
						$Email = $arrBookingResults['data'][0]['Email'];
						//$Role = $arrBookingResults['data'][0]['Role'];
						$Role = array("Group Coach");
						//array_push($Role,"Talent");
						$criteria="((Email:equals:".$Email."))";
						$arrParams['criteria']=$criteria;
						$respSearchNewsletter=$objZoho->searchRecords("Hired_Coaches",$arrParams);
						
							$arrZohoCoach=[
								"Coach_Name"=>$idd,
								"Coach_Email"=>$Email,
								"Email"=>$Email,
								"Total_Cost"=>$total,
								"Default_Hire_Price"=>$total,
								"Total_Cost_Inc_GST_A"=>$total,
								"Cost_Type"=>"Overall",
								"Overall_Cost"=>$total,
								"Role"=>$Role,
								"Admin_Notes"=>$Admin_Notes,
							];
							$arrInsertContact[]=$arrZohoCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches",$arrInsertContact,$arrTrigger);
							$ContactID = $respInsertContact['data'][0]['details']['id'];
						
						
						$arrHCoach=[
								"Camps"=>$camps,
								"Cost"=>$total,
								"New_Cost"=>$total,
								"Hired_Coaches"=>$ContactID,
								
							];
							$arrInsertHContact[]=$arrHCoach;
							$arrTrigger=["workflow"];
							$respInsertContact=$objZoho->insertRecord("Hired_Coaches_X_Camps",$arrInsertHContact,$arrTrigger);
							$Hired_Coaches_X_Camps[] = $respInsertContact['data'][0]['details']['id'];
							$Coaches[$zohoid] =  $ContactID;
						
						
						for($x = 1; $x <= 3; $x++) {
							
							if($x == 1){
								$dayavail = ($day1avail == 1 ? true : false);
								$dayprice = $day1price;
							}
							if($x == 2){
								$dayavail = ($day2avail == 1 ? true : false);
								$dayprice = $day2price;
							}
							if($x == 3){
								$dayavail = ($day3avail == 1 ? true : false);
								$dayprice = $day3price;
							}
							$arrNewsLetter['Camp']=$camps;
							$arrNewsLetter['Coach']=$ContactID;
							$arrNewsLetter['Coach_attend']=$dayavail;
							$arrNewsLetter['Day_Number']=(string)$x;
							$arrNewsLetter['Day_Price']=$dayprice;
							$arrNewsLetter['Coach_Confirm']=false;
							$arrNewsLetter['Total']=$total;
							$arrInsertNewsletter=[];
							$arrInsertNewsletter[]=$arrNewsLetter;
							$arrTrigger=["workflow"];
							$respInsertNewsletter=$objZoho->insertRecord("Coach_Attendance",$arrInsertNewsletter,$arrTrigger);
							$Att_ID = $respInsertNewsletter['data'][0]['details']['id'];
							$Att[] = $Att_ID;
						}	
					}
				}
				$arrNewsLetter = []; 
				$arrInsertNewsletter = []; 
				$arrHCoach = []; 
				$arrZohoCoach = []; 
				$arrInsertContact = []; 
				$arrBookingResults = "";
			}
			
			for($x = 0; $x < $grp; $x++) {
				$groupname = $_POST['grp'.$x]; 
				$day1coach = $_POST['coach1'.$x];
				$day2coach = $_POST['coach2'.$x];
				$day3coach = $_POST['coach3'.$x];
				$i = 0;
				foreach($day1coach as $c1){
					$coachid = $Coaches[$c1];
					$arrCoach=[];
					$arrCoach[]=array("Group_Number" => $groupname);
					$arrTrigger=["workflow"];
					$respUpdateNewsletter=$objZoho->updateRecord("Hired_Coaches",$coachid,$arrCoach,$arrTrigger);
					$arrCoach=[];
					
					$arrBookingResults=$objZoho->getRecordById("Hired_Coaches",$Coaches[$c1]);
					if(count($arrBookingResults['data'])){
						$Role = $arrBookingResults['data'][0]['Role'][0];
						$cg[$i]['Coach_Name'] = $Coaches[$c1];
						$cg[$i]['Role'] = $Role;
						$cg[$i]['Day_No'] = "Day 1";
						$i++;
						
					}
				}
				foreach($day2coach as $c2){
					$coachid = $Coaches[$c2];
					$arrCoach=[];
					$arrCoach[]=array("Group_Number" => $groupname);
					$arrTrigger=["workflow"];
					$respUpdateNewsletter=$objZoho->updateRecord("Hired_Coaches",$coachid,$arrCoach,$arrTrigger);
					$arrCoach=[];
					
					$arrBookingResults=$objZoho->getRecordById("Hired_Coaches",$Coaches[$c2]);
					if(count($arrBookingResults['data'])){
						$Role = $arrBookingResults['data'][0]['Role'][0];
						$cg[$i]['Coach_Name'] = $Coaches[$c2];
						$cg[$i]['Role'] = $Role;
						$cg[$i]['Day_No'] = "Day 2";
						$i++;
						
					}
				}
				foreach($day3coach as $c3){
					$coachid = $Coaches[$c3];
					$arrCoach=[];
					$arrCoach[]=array("Group_Number" => $groupname);
					$arrTrigger=["workflow"];
					$respUpdateNewsletter=$objZoho->updateRecord("Hired_Coaches",$coachid,$arrCoach,$arrTrigger);
					$arrCoach=[];
					
					$arrBookingResults=$objZoho->getRecordById("Hired_Coaches",$Coaches[$c3]);
					if(count($arrBookingResults['data'])){
						$Role = $arrBookingResults['data'][0]['Role'][0];
						$cg[$i]['Coach_Name'] = $Coaches[$c3];
						$cg[$i]['Role'] = $Role;
						$cg[$i]['Day_No'] = "Day 3";
						$i++;
						
					}
				}
				
				$arrInsertContact = array();
				$arrZohoCoach = array();
				$arrZohoCoach=[
					"Group_Name"=>$groupname,
					"Camp_ID"=>$camps,
					"Coach_Group"=>$cg,
				];	
				$arrInsertContact[]=$arrZohoCoach;
				$arrTrigger=["workflow"];
				$respInsertContact=$objZoho->insertRecord("Participant_Group",$arrInsertContact,$arrTrigger);
				$ContactID = $respInsertContact['data'][0]['details']['id'];
			}
	
	
	/*Insert again process*/
	$str = json_encode($Hired_Coaches_X_Camps);
	$qrySel="UPDATE `camp_group` SET `CC`='$str',`date`='$date' WHERE `ID` = '$id_camp'";
	$result = mysqli_query($con, $qrySel);
	
	}
}			
catch(Exception $e){
	$ResponseData = null;
}


echo "<script type='text/javascript'> document.location = 'list_camp_group.php?success2=1'; </script>";







?>