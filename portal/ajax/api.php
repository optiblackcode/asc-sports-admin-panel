<?php
include "../include/common.php";
require_once('../../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );

function date_compare($element1, $element2) {
    $datetime1 = strtotime($element1['date']);
    $datetime2 = strtotime($element2['date']);
    return $datetime1 - $datetime2;
} 
if(isset($_POST)){
	$api = $_POST['api'];
	if($api == 'get_groups'){
		$id = $_POST['id'];
		$name = $_POST['name'];
		$camp_arr = array();
		$objZoho=new ZOHO_METHODS();
		try{	
			if($objZoho->checkTokens()){
				$k = 0;
				$criteria="(Camp:equals:".$name.")";
				$arrParams['criteria']=$criteria;
				$Camps=$objZoho->searchRecords("Participant_Group",$arrParams);
				if(count($Camps['data'])){
					
					foreach($Camps['data'] as $Camp){
						$camp_arr[$k]['id'] = $Camp['id'];
						$camp_arr[$k]['Name'] = $Camp['Group_Name'];
						$k++;
					}
				}	
			}
		}			
		catch(Exception $e){
			$ResponseData = null;
		}
		echo json_encode(array("Code" => 200 ,"Data" => $camp_arr,"Count" => $k));	
	}
	if($api == 'get_camps'){
		$id = $_POST['id'];
		$date = $_POST['date'];
		$name = $_POST['name'];
		$camp_arr = array();
		$objZoho=new ZOHO_METHODS();
		try{	
			if($objZoho->checkTokens()){
				$Sports = "";
				$State = "";
				$arrVenues_BookedResults=$objZoho->getRecordById("Venues_Booked",$id);
				if(count($arrVenues_BookedResults['data'])){
					$State = $arrVenues_BookedResults['data'][0]['State'];
				}
				
				$criteria="((Venue_Booked_Name:equals:".$id.") and (Status:equals:Current))";
				$arrParams['criteria']=$criteria;
				$Camps=$objZoho->searchRecords("Camps",$arrParams);
				if(count($Camps['data'])){
					$k = 0;
					foreach($Camps['data'] as $Camp){
						if($date != ""){
							if($date == $Camp['Camp_Dates']){
								$camp_arr['Camp'][$k]['id'] = $Camp['id'];
								$camp_arr['Camp'][$k]['Name'] = $Camp['Name'];
								$camp_arr['Camp'][$k]['SKU'] = $Camp['SKU'];
								$camp_arr['Camp'][$k]['Camp_Dates'] = $Camp['Camp_Dates'];
								$camp_arr['Camp'][$k]['state'] = $Camp['State'];
								$camp_arr['Camp'][$k]['nob'] = 0;
								$camp_arr['Camp'][$k]['nop'] = 0;
								$camp_arr['Camp'][$k]['Suburb'] =  (is_array($Camp['Suburb']) ? $Camp['Suburb']['name'] : "");
								$camp_arr['Camp'][$k]['Sports'] =  (is_array($Camp['Sports']) ? $Camp['Sports']['name'] : "");
								$Sports = (is_array($Camp['Sports']) ? $Camp['Sports']['name'] : "");
								$Booking_Id = array();
								$campName = $Camp['id'];
								$criteria="(Camp_Name:equals:".$campName.")";
								$arrParams['criteria']=$criteria;
								$Participant=$objZoho->searchRecords("Participant",$arrParams);
								if(count($Participant['data'])){
									$camp_arr['Camp'][$k]['nop'] = count($Participant['data']);
									foreach($Participant['data'] as $Part){
										$Booking_Id[] =  $Part['Booking_Id']['name'];
									}
								}
								$camp_arr['Camp'][$k]['nob'] = count(array_unique($Booking_Id));
								$k++;
							}
						}
						else{
							$camp_arr['Camp'][$k]['id'] = $Camp['id'];
							$camp_arr['Camp'][$k]['Name'] = $Camp['Name'];
							$camp_arr['Camp'][$k]['Camp_Dates'] = $Camp['Camp_Dates'];
							$camp_arr['Camp'][$k]['state'] = $Camp['State'];
							$camp_arr['Camp'][$k]['SKU'] = $Camp['SKU'];
							+                                                                                                                             
							$camp_arr['Camp'][$k]['nob'] = 0;
							$camp_arr['Camp'][$k]['nop'] = 0;
							$camp_arr['Camp'][$k]['Suburb'] =  (is_array($Camp['Suburb']) ? $Camp['Suburb']['name'] : "");
							$camp_arr['Camp'][$k]['Sports'] =  (is_array($Camp['Sports']) ? $Camp['Sports']['name'] : "");
							$Sports = (is_array($Camp['Sports']) ? $Camp['Sports']['name'] : "");
							$Booking_Id = array();
							$campName = $Camp['id'];
							$criteria="(Camp_Name:equals:".$campName.")";
							$arrParams['criteria']=$criteria;
							$Participant=$objZoho->searchRecords("Participant",$arrParams);
							if(count($Participant['data'])){
								$camp_arr['Camp'][$k]['nop'] = count($Participant['data']);
								foreach($Participant['data'] as $Part){
									$Booking_Id[] =  $Part['Booking_Id']['name'];
								}
							}
							$camp_arr['Camp'][$k]['nob'] = count(array_unique($Booking_Id));
							$k++;
						}
					}
				}	
				
				//$criteria="((Sports:equals:".$Sports.") and (State:equals:".$State."))";
				$j = 0;
				$host="localhost";
				$username="RBUH9jPnna";
				$password="BYdxh5JIu!";
				$db="asc_datastudio_reportingnew";
				$con = mysqli_connect("localhost",$username,$password,$db);
				
				$qrySel ="SELECT * FROM `coaches`";
				$result = mysqli_query($con, $qrySel);
				while($rawdata = mysqli_fetch_assoc($result)){
					$camp_arr['Coaches'][$j]['id'] =  $rawdata['ZOHOID'];
					$camp_arr['Coaches'][$j]['Name'] = $rawdata['name'];
					$camp_arr['Coaches'][$j]['role'] = $rawdata['Role'];
					$camp_arr['Coaches'][$j]['Primary_Phone'] = $rawdata['Primary_Phone'];
					$camp_arr['Coaches'][$j]['State'] = $rawdata['State'];
					$j++;
				}
				
				
				/*$j = 0;
				for ($x = 1; $x <= 3; $x++) {
			$arrParams = array();
				$arrParams['page'] = $x;
				 $criteria="(State:equals:".$State.")";
				$arrParams['criteria']=$criteria;
				$Coaches=$objZoho->searchRecords("Coaches",$arrParams);
				
				if(count($Coaches['data'])){
					
					foreach($Coaches['data'] as $Coach){
						$camp_arr['Coaches'][$j]['id'] = $Coach['id'];
						$camp_arr['Coaches'][$j]['Name'] = $Coach['Name']; 
						$camp_arr['Coaches'][$j]['role'] = implode(",",$Coach['Role']); 
						$camp_arr['Coaches'][$j]['Primary_Phone'] = $Coach['Primary_Phone']; 
						$camp_arr['Coaches'][$j]['State'] = $Coach['State']; 
						$j++;
					}
				}
				}*/
				
			}
		}			
		catch(Exception $e){
			$ResponseData = null;
			echo "heer";
			
		}
		echo json_encode(array("Code" => 200 ,"Data" => $camp_arr));
	}

	if($api == 'get_dates'){
		$id = $_POST['id'];
		
		$name = $_POST['name'];
		$camp_arr = array();
		$objZoho=new ZOHO_METHODS();
		try{	
			if($objZoho->checkTokens()){
				$Sports = "";
				$State = "";
				$arrVenues_BookedResults=$objZoho->getRecordById("Venues_Booked",$id);
				if(count($arrVenues_BookedResults['data'])){
					$State = $arrVenues_BookedResults['data'][0]['State'];
				}
				$criteria="((Venue_Booked_Name:equals:".$id.") and (Status:equals:Current))";
				$arrParams['criteria']=$criteria;
				$Camps=$objZoho->searchRecords("Camps",$arrParams);
				if(count($Camps['data'])){
					$k = 0;
					foreach($Camps['data'] as $Camp){
						$camp_arr[$k]['Camp_Dates'] = $Camp['Camp_Dates'];
						$k++;
					}
				}
				foreach ( $camp_arr AS $key => $line ) { 
					if ( !in_array($line['Camp_Dates'], $usedFruits) ) { 
						$usedFruits[] = $line['Camp_Dates']; 
						$newArray[$key] = $line; 
					} 
				} 
				
			}
		}			
		catch(Exception $e){
			$ResponseData = null;
		
			
		}
		echo json_encode(array("Code" => 200 ,"Data" => $newArray));
	}


	if($api == 'get_coach_wwcc'){
		$id = $_POST['id']; 
		$camp_arr = array();
		$camp_arr['Working_With_Children_Check'] = "";
		$camp_arr['Working_with_children_card_No'] = "";
		$camp_arr['Remarks_for_WWCC'] = "";
		$camp_arr['Expiry_Date'] = "";
		$camp_arr['Hired_Price'] = 0;
		
		$objZoho=new ZOHO_METHODS();
		try{	
			if($objZoho->checkTokens()){
				$k = 0;
				$Working_With_Children_Check = "";
				$Working_with_children_card_No = "";
				$Remarks_for_WWCC = "";
				$Expiry_Date = "";
				$arrCoach=$objZoho->getRecordById("Coaches",$id);
				if(count($arrCoach['data'])){
					$Coach = $arrCoach['data'][0];
					$Coach_Email = $Coach['Email'];
					$camp_arr['Working_With_Children_Check'] = $Coach['Working_With_Children_Check'];
					$camp_arr['Working_with_children_card_No'] = $Coach['Working_with_children_card_No'];
					$camp_arr['Remarks_for_WWCC'] = $Coach['Remarks_for_WWCC'];
					$camp_arr['Expiry_Date'] = $Coach['Expiry_Date'];
					$camp_arr['Hired_Price'] = 0;
					$prices = array();
					$criteria="(Email:equals:".$Coach_Email.")";
					$arrParams['criteria']=$criteria;
					$Camps=$objZoho->searchRecords("Hired_Coaches",$arrParams);
					if(count($Camps['data'])){
						foreach($Camps['data'] as $d){
							$prices[] = $d['Overall_Cost'];
						}
						$camp_arr['Hired_Price'] = max($prices);
					}	
					
					
					$k = 1;
					
				}	
			}
		}			
		catch(Exception $e){
			$ResponseData = null;
		}
		echo json_encode(array("Code" => 200 ,"Data" => $camp_arr,"Count" => $k));	
	}
  
  
  if($api == 'get_all_data'){
    
    $VB_array = array();
    $HC_array = array();
    $host="localhost";
    $username="RBUH9jPnna";
    $password="BYdxh5JIu!";
    $db="asc_datastudio_reportingnew";
    $con = mysqli_connect("localhost",$username,$password,$db);
    $date = date("Y-m-d H:i:s");



    $objZoho=new ZOHO_METHODS();
    try{	
      if($objZoho->checkTokens()){
        
        for ($x = 1; $x <= 2; $x++) {
          $arrParams = array();
          $arrParams['page'] = $x;
          $criteria = "((Status:equals:Current) and ((Business_Arm:equals:ASC) or (Business_Arm:equals:CSC)))";

          $arrParams['criteria'] = $criteria;
          $Camps = $objZoho->searchRecords("Camps", $arrParams);
          //console.log("Jsondata");
          if(count($Camps['data'])){
             
            $l = 0;
            
            foreach($Camps['data'] as $Camp){
              $camp[$l]['Name'] = $Camp['Name'];
              $camp[$l]['Camp_Dates'] = $Camp['Camp_Dates'];
              $camp_arr[$l] = $Camp['Camp_Dates'];
              $VB_array[$l]['name'] = $Camp['Venue_Booked_Name']['name'];
              $VB_array[$l]['id'] = $Camp['Venue_Booked_Name']['id'];
              $l++;
            }
          }
        }
        
        foreach ( $VB_array AS $key => $line ) { 
          if ( !in_array($line['name'], $usedFruits) ) { 
            $usedFruits[] = $line['name']; 
            $newVB_array[$key] = $line; 
          } 
        } 
        
        
        
        $cos = array();
        $cd = array();
        $fa = array();
        $j = 0;
        $o = 0;
        $d = 0;
        $f = 0;
        $qrySel ="SELECT * FROM `coaches`";
        $result = mysqli_query($con, $qrySel);
        
        while($rawdata = mysqli_fetch_assoc($result)){
          $HC_array[$j]['id'] =  $rawdata['ZOHOID'];
          $HC_array[$j]['name'] = $rawdata['name'];
          $HC_array[$j]['role'] = $rawdata['Role'];
          $HC_array[$j]['Primary_Phone'] = $rawdata['Primary_Phone'];
          $HC_array[$j]['State'] = $rawdata['State'];
          $j++;
        }
        //echo "<pre>";
    //print_r($HC_array); die;
        
        
      }
    }			
    catch(Exception $e){ 
      $ResponseData = null;
    }

    $camp_arr = array_unique($camp_arr);

    $final_result = [];

    $final_result['newVB_array'] = $newVB_array;
    $final_result['HC_array'] = $HC_array;
    $final_result['camp_arr'] = $camp_arr;

    echo json_encode($final_result);
    
    
  }
  
}
	

?>