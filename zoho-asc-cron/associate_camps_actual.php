<?php
request_log("");
function request_log($log)
{
	$log=date("Y-m-d H:i:s")."\t".$log;
	$log.="\n**********************************************************";
	$file=fopen("school_booked_request_log_actual.log", "a+");
	fwrite($file, $log);
	fclose($log);
}

include "curl_zoho/class/zoho_methods.class.php";
include "class/camps.class.php";
include "class/schools_booked.class.php";
include "class/camp_association.class.php";

$objZohoCamps=new CAMPS();
$objZohoSB=new SCHOOLS_BOOKED();
$objZoho=new ZOHO_METHODS();
$objZohoSBCampAss=new CAMP_ASSOCIATION();

if($objZoho->checkTokens())
{
	/*// ******************************************************************
	$arrParams=[];
	$rslt=$objZoho->getRelatedRecords("Camps","3108913000001801154","Schools_Booked_2",$arrParams);
	echo "<pre>";
	print_r($rslt);

	// ******************************************************************
	die();*/

	// *************** Get all current school booked and insert in database **************
	$moreRecord=true;
	$arrAllSB=[];
	$maxCount=40;
	$i=1;
	while($i<=$maxCount && $moreRecord)
	{
		// echo $i."<br>";
		// Get records from camp associate status 3108913000002161104
		//$criteria="((Camps_Associated:equals:false))";
		//$arrParams['criteria']=$criteria;
		$arrParams['cvid']="3108913000002161104";
		$arrParams['page']=$i;
		$arrParams['per_page']=200; 
		$resp=$objZoho->getRecords("Schools_Booked",$arrParams);
		if(isset($resp['data']))
		{
			if(count($resp['data'])>0)
			{
				foreach ($resp['data'] as $key => $value) 
				{
					$arrAllSB[]=$value;
				}
				if(isset($resp['info']))
				{
					if($resp['info']['more_records']!=1)
					{
						$moreRecord=false;		
					}
				}
				else
				{
					$moreRecord=false;		
				}
			}
			else
			{
				$moreRecord=false;		
			}
		}
		else
		{
			$moreRecord=false;
		}
		$i++;
	}
	/*echo "<pre>";
	print_r($arrAllSB);
	die();*/
	if(count($arrAllSB)>0)
	{

		// Insert allSchool Booked in database
		$rsltInsert=$objZohoSB->insert($arrAllSB);
		if($rsltInsert)	
		{
			//echo "School Booked inserted successfully.";
		}

		// Get all current camps from zoho
		$moreRecord=true;
		$arrAllCamps=[];
		$maxCount=10;
		$i=1;
		while($i<=$maxCount && $moreRecord)
		{
			// echo $i."<br>";
			// Get records from camp status
			$criteria="((Camp_Status:equals:Bookings Available) or (Camp_Status:equals:Running) or (Camp_Status:equals:Planned))";
			//$criteria="((Season:equals:Winter) and (Year:equals:2021))";
			$arrParams_s['criteria']=$criteria;
			$arrParams_s['page']=$i;
			$arrParams_s['per_page']=200; 
			$resp=$objZoho->searchRecords("Camps",$arrParams_s);
			if(isset($resp['data']))
			{
				if(count($resp['data'])>0)
				{
					foreach ($resp['data'] as $key => $value) 
					{
						$arrAllCamps[]=$value;
					}
					if(isset($resp['info']))
					{
						if($resp['info']['more_records']!=1)
						{
							$moreRecord=false;		
						}
					}
					else
					{
						$moreRecord=false;		
					}
				}
				else
				{
					$moreRecord=false;		
				}
			}
			else
			{
				$moreRecord=false;
			}
			$i++;
		}
		// Insert all current camps in database
		$rsltInsert=$objZohoCamps->insert($arrAllCamps);
		if($rsltInsert)
		{
			// echo "Camps inserted successfully.";
		}
		else
		{
			// echo "Failed to insert camps.";
		}

		// Create association of each school booked with a camp
		$rsltSchoolBooked=$objZohoSB->getAll();
		while ($rowSchoolBooked=$rsltSchoolBooked->fetch_assoc()) 
		{
			$blAssociation=false;
			$arrSBAssociation=[];
			$schoolRegion=$rowSchoolBooked['sb_region'];
			$schoolState=$rowSchoolBooked['sb_state'];
			if(trim($schoolRegion)!="")
			{
				$rsltCamps=$objZohoCamps->getCampsByRegion($schoolRegion);
				$blAssociation=true;
			}
			else
			{
				if($schoolState=="QLD" || $schoolState=="SA" || $schoolState=="TAS" || $schoolState=="ACT")
				{
					$rsltCamps=$objZohoCamps->getCampsByState($schoolState);
					$blAssociation=true;
				}

			}

			if($blAssociation)
			{
				if($rsltCamps)
				{
					if($rsltCamps->num_rows!=0)
					{
						$noOfCamps=$rsltCamps->num_rows;
						$perCampCost=$rowSchoolBooked['sb_cost']/$noOfCamps;
						while ($rowCamps=$rsltCamps->fetch_assoc()) 
						{
							$arrAssociation=[];
							$arrAssociation['camps_id']=$rowCamps['zoho_camp_id'];
							$arrAssociation['camp_name']=$rowCamps['zoho_camp_name'];
							$arrAssociation['sb_id']=$rowSchoolBooked['sb_id'];
							$arrAssociation['sb_name']=$rowSchoolBooked['sb_name'];
							$arrAssociation['sb_cost']=$perCampCost;
							$arrAssociation['region']=$schoolRegion;
							$arrAssociation['state']=$schoolState;

							// Put associations in an array for each school
							$arrSBAssociation[]=$arrAssociation;

						}
						$rsltCampAssociation=$objZohoSBCampAss->insert($arrSBAssociation);
						if($rsltCampAssociation)
						{
							// echo "Camp Association inserted.";
							// echo "<br>";
						}
					}
				}
				// echo "School Name : ".$rowSchoolBooked['sb_name'];
				// echo "<br>";
				// echo "<pre>";
				// print_r($arrSBAssociation);
			}
		}

		// Get all school booked associations and insert in zoho
		$recordCount=1;
		$arrAllSBCamp=[];
		$rsltCampAssociation=$objZohoSBCampAss->getAll();
		while($rowCampAssociation=$rsltCampAssociation->fetch_assoc())
		{
			$arrSBCamp=[];
			$arrSBCamp['Camps']=$rowCampAssociation['zoho_camp_id'];
			$arrSBCamp['School_Booked']=$rowCampAssociation['sb_id'];
			$arrSBCamp['Cost']=$rowCampAssociation['sb_cost'];
			$arrAllSBCamp[]=$arrSBCamp;
			$recordCount++;
			if($recordCount>=95)
			{
				$recordCount=0;
				$resp=$objZoho->insertRecord("School_Booked_Camps",$arrAllSBCamp);		
				// echo "<pre>";
				// echo "In side loop";
				// print_r($resp);
				$arrAllSBCamp=[];
			}
		}
		$noOfCampAssociations=$rsltCampAssociation->num_rows;
		echo $noOfCampAssociations." camp associations created.\n";
		if(count($arrAllSBCamp)!=0)
		{
			$resp=$objZoho->insertRecord("School_Booked_Camps",$arrAllSBCamp);		
			// echo "<pre>";
			// echo "Out side loop";
			// print_r($resp);
			$arrAllSBCamp=[];
		}
		// Get all camps associations and update school costs in camps
		$arrCampsToUpdate=[];
		$rsltCampAssociation=$objZohoSBCampAss->getAll();
		while($rowCampAssociation=$rsltCampAssociation->fetch_assoc())
		{
			$arrCampsToUpdate[]=$rowCampAssociation['zoho_camp_id'];
		}

		// Recalculate school cost for camps
		$arrUpdatedCamps=[];
		$arrCampsToUpdate=array_unique($arrCampsToUpdate);
		foreach ($arrCampsToUpdate as $key => $camp_id) 
		{	

			// Recalculate school cost for the camp
			$arrParams=[];
			$respSB=$objZoho->getRelatedRecords("Camps",$camp_id,"Schools_Booked_2",$arrParams);
			$schoolCost=0;
			if($respSB)
			{
				if(count($respSB['data'])>0)
				{
					foreach ($respSB['data'] as $key => $arrAssociation) 
					{
						if(is_array($arrAssociation['School_Booked']))
						{
							$cost=$arrAssociation['Cost'];
							if($cost=="")
							{
								$cost=0;
							}
							$schoolCost=$schoolCost+$cost;
						}
					}
				}
			}
			// Updated camp info
			$arrCamp=[];
			$gross_margin=0;
			$gross_margin_perc=0;
			$revenue_ration=0;
			// Get camp cost and revenue for recalculations
			$rsltSingleCamp=$objZohoCamps->getCampByZohoId($camp_id);
			if($rsltSingleCamp)
			{
				if($rsltSingleCamp->num_rows>0)
				{
					$rowSingleCamp=$rsltSingleCamp->fetch_assoc();
					$total_cost=$rowSingleCamp['total_cost'];
					$school_advertising_cost=$rowSingleCamp['school_advertising_cost'];
					$total_cost=$total_cost-$school_advertising_cost;
					$total_cost=$total_cost+$schoolCost;

					$earned_revenue=$rowSingleCamp['earned_revenue'];
					$gross_margin=$earned_revenue-$total_cost;
					// Revenue Ration
					if($total_cost==0)
					{
						$revenue_ration=0;
					}
					else
					{
						if($earned_revenue==0)
						{
							$revenue_ration=0;
						}
						else
						{
							$revenue_ration=$earned_revenue/$total_cost*100;
						}
					}
					// Gross margin percenatge 
					if($total_cost!=0 && $earned_revenue!=0)
					{
						if($gross_margin>=0)
						{
							$gross_margin_perc=($earned_revenue-$total_cost)/$earned_revenue*100;
						}
					}
					$arrCamp['Gross_Margin']=round($gross_margin,2);
					$arrCamp['Gross_Margin_A']=round($gross_margin_perc,2);
					$arrCamp['Revenue_Ration']=round($revenue_ration,2);
					$arrCamp['Total_Cost']=round($total_cost,2);
				}
			}
			$arrCamp['id']=$camp_id;
			$arrCamp['School_Advertising_Cost']=$schoolCost;

			$arrUpdatedCamps[]=$arrCamp;
		}
		$noOfCampsUpdated=count($arrCampsToUpdate);
		echo $noOfCampsUpdated." camps updated.";
		// Update school cost of camp in zoho
		$recordCount=1;
		$arrCampsBatch=[];
		foreach ($arrUpdatedCamps as $key => $arrCamp) 
		{
			$recordCount++;
			$arrCampsBatch[]=$arrCamp;
			if($recordCount>=95)
			{
				$recordCount=0;
				$resp=$objZoho->bulkUpdateRecords("Camps",$arrCampsBatch);
				// echo "Camp school cost updated : inside loop.";
				$arrCampsBatch=[];
			}	
		}
		if(count($arrCampsBatch)>0)
		{
			$recordCount=0;
			$resp=$objZoho->bulkUpdateRecords("Camps",$arrCampsBatch);
			// echo "Camp school cost updated : outside loop.";
			$arrCampsBatch=[];
		}
		// Delete All school association from database
		if($objZohoSBCampAss->deleteAll())
		{
			// echo "Camp Associations deleted.<br/>";
		}

		// Delete all camps
		if($objZohoCamps->deleteAll())
		{
			// echo "Camps deleted.<br/>";
		}
		// Get All school booked and update it's status to camps associated
		$recordCount=1;
		$arrAllSBUpdate=[];
		$rsltSchoolBooked=$objZohoSB->getAll();
		if($rsltSchoolBooked)
		{
			if($rsltSchoolBooked->num_rows!=0)
			{
				while ($rowSchoolBooked=$rsltSchoolBooked->fetch_assoc()) 
				{

					$arrSBUpdate=[];
					$arrSBUpdate['id']=$rowSchoolBooked['sb_id'];
					$arrSBUpdate['Camps_Associated']=true;

					$arrAllSBUpdate[]=$arrSBUpdate;
					$recordCount++;
					if($recordCount>=95)
					{
						$recordCount=0;
						$resp=$objZoho->bulkUpdateRecords("Schools_Booked",$arrAllSBUpdate);		
						$arrAllSBUpdate=[];
						// echo "<pre>";
						// echo "In side loop Updated school booked : ";
						// print_r($resp);
					}

				}
				if(count($arrAllSBUpdate)>0)
				{
					$resp=$objZoho->bulkUpdateRecords("Schools_Booked",$arrAllSBUpdate);		
					$arrAllSBUpdate=[];
					// echo "<pre>";
					// echo "Out side loop Updated school booked : ";
					// print_r($resp);
				}
			}
		}
		else
		{

		}
		if($objZohoSB->deleteAll())
		{
			// echo "Schools booked deleted.<br/>";
		}
		
	}
	else
	{
		echo "No school booked record to associate.";
	}

}
else
{
	echo "Error in api : Token expired";
}
?>