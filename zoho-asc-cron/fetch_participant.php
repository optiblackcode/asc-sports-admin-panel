<?php
require_once("common.php");
$con = mysqli_connect("localhost","test","Admin@903324","asc_datastudio_reportingnew");

$objZoho=new ZOHO_METHODS();
	try{
		if($objZoho->checkTokens()){
			for ($x = 1; $x <= 12; $x++) {
				$arrParams = array();
				$arrParams['page'] = $x;
				
				$criteria="((Year:equals:2022))";
				$arrParams['criteria']=$criteria;
				$dataEI = $objZoho->searchRecords("Participant",$arrParams);
				
				if(count($dataEI['data'])){
					$j = 1;
					foreach($dataEI['data'] as $d){
						$p = $d['id'];
						echo"<br/>------<br/>";
						echo $sql = "SELECT * FROM `zoho_participants` WHERE `participant_id` = '$p'";
						echo "<br/>";
						$result = mysqli_query($con, $sql);
						echo mysqli_num_rows($result);
						echo"<br/>------";
						$j++;
					}
					echo "Total Row :".$j;
				}
			}	
				
		}
	}	
	catch(Exception $e)
	{
		$crmLog.="Exception : ".$e->getMessage().", ";
		$success=false;
	}
?>