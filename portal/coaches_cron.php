<?php
//include "include/common.php";

require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );


$host="localhost";
	$username="RBUH9jPnna";
	$password="BYdxh5JIu!";
	$db="asc_datastudio_reportingnew";
	$con = mysqli_connect("localhost",$username,$password,$db);
	$date = date("Y-m-d H:i:s");


$VB_array = array();
$HC_array = array();
$objZoho=new ZOHO_METHODS();
try{	
	if($objZoho->checkTokens()){
		
		for ($x = 1; $x <= 25; $x++) {
			$arrParams = array();
			$arrParams['page'] = $x;
			$Hired_Coaches=$objZoho->getRecords("Coaches",$arrParams);
			if(count($Hired_Coaches['data'])){
				echo $x;
				echo "<br/>";
				foreach($Hired_Coaches['data'] as $HC){
					$HC_array[$j]['id'] =  $HC['id'];
					$HC_array[$j]['name'] = $HC['Name'];
					$HC_array[$j]['role'] = implode(",",$HC['Role']);
					$HC_array[$j]['Primary_Phone'] = $HC['Primary_Phone'];
					$HC_array[$j]['State'] = $HC['State'];
					$j++;
					$id = "ZOHO_".$HC['id'];
					$Email = $HC['Email'];
					echo "SELECT * FROM `coaches` WHERE `Email` = '$Email'";
					echo "<br/>";
					$s = mysqli_query($con,"SELECT * FROM `coaches` WHERE `Email` = '$Email'");
					echo $count = mysqli_num_rows($s);
					if($count == 0){
						echo "0 ".$Email." <br/>";
						echo $qrySel="INSERT INTO `coaches`(`ZOHOID`,`ZID`, `Email`, `name`, `Role`, `Primary_Phone`, `State`, `Date`) VALUES ('". $HC['id']."','". $id."','". $HC['Email']."','". $HC['Name']."','". implode(",",$HC['Role'])."','". $HC['Primary_Phone']."','". $HC['State']."','$date')";
						$result = mysqli_query($con, $qrySel);
					}
					else{
						echo "1 ".$Email." <br/>";
					}
					
					
					
				}
			}
		}
	}
}			
catch(Exception $e){
	$ResponseData = null;
}		