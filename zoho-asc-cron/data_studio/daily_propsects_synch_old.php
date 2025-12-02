<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time',300);

require_once __DIR__."/class/execution_logs.class.php";
$objExecutionLog=new EXECUTION_LOGS();
$execusitonLogId=$objExecutionLog->log_id;

require_once __DIR__."/../curl_zoho/class/zoho_methods.class.php";
require_once __DIR__."/class/zoho_prospects.class.php";

$objZoho=new ZOHO_METHODS();
$objProspect=new ZOHO_PROSPECTS();
try
{
	if($objZoho->checkTokens())
	{
		echo "<h1>Prospects</h1>";
		// ************ Get all prospects and insert or update in database ***********
		$prospectsCreated=0;
		$prospectsUpdated=0;
		$moreRecord=true;
		$maxCount=500;
		// $maxCount=2;
		$i=1;
		while($i<=$maxCount && $moreRecord)
		{
			// Get records from prospects with flag 3108913000018410071
			$arrParams=[];
			$arrParams['cvid']="3108913000018410071";
			$arrParams['page']=$i;
			$arrParams['per_page']=200; 
			$resp=$objZoho->getRecords("Prospects",$arrParams);
			if(isset($resp['data']))
			{
				if(count($resp['data'])>0)
				{
					foreach ($resp['data'] as $key => $arrZohoProspect) 
					{
						// Insert/Update booking in database
						$arrDBProspect=[];
						$arrDBProspect["prospect_id"]=$arrZohoProspect["id"];
						$arrDBProspect["propsect_email"]=$arrZohoProspect["Email"];
						$arrDBProspect["business_arm"]=$arrZohoProspect["Business_Arm"];
						if($arrZohoProspect["Date_Time"]!="")
						{
							$arrDBProspect["date_time"]=$arrZohoProspect["Date_Time"];
						}
						$arrDBProspect["form"]=$arrZohoProspect["Form"];
						if($arrZohoProspect["Contact_Us_Fill_Date"]!="")
						{
							$arrDBProspect["contact_us_fill_date"]=$arrZohoProspect["Contact_Us_Fill_Date"];
						}
						if($arrZohoProspect["Newsletter_Signup_Date"]!="")
						{
							$arrDBProspect["newsletter_signup_date"]=$arrZohoProspect["Newsletter_Signup_Date"];
						}
						if($arrZohoProspect["Download_Brochure_Date"]!="")
						{
							$arrDBProspect["download_brochure_date"]=$arrZohoProspect["Download_Brochure_Date"];
						}
						if($arrZohoProspect["Camp_Booking_Date"]!="")
						{
							$arrDBProspect["camp_booking_date"]=$arrZohoProspect["Camp_Booking_Date"];
						}
						if($arrZohoProspect["Notify_Me_Date"]!="")
						{
							$arrDBProspect["notify_me_date"]=$arrZohoProspect["Notify_Me_Date"];
						}
						$arrDBProspect["notify_me_state_sports"]=$arrZohoProspect["Notify_Me_Sports_States"];
						$arrDBProspect["days_from_brochure_download"]=$arrZohoProspect["Days_From_Brochure_Download"];
						$arrDBProspect["is_current_parent"]=$arrZohoProspect["Is_Current_Parent"];
						$arrDBProspect["is_parent"]=$arrZohoProspect["Is_Parent"];
						$arrDBProspect["is_subscriber"]=$arrZohoProspect["Is_Subscriber"];

						$arrDBProspect["raw_data"]=json_encode($arrZohoProspect);
						$arrDBProspect["flag_complete"]='0';

						$rsltProspect=$objProspect->getProspectByZohoId($arrDBProspect['prospect_id']);
						if($rsltProspect)
						{
							if($rsltProspect->num_rows>0)
							{
								$rowFoundProspect=$rsltProspect->fetch_assoc();
								$recId=$rowFoundProspect['rec_id'];

								// Update booking
								$rsltUpdate=$objProspect->updateProspect($recId,$arrDBProspect);
								if($rsltUpdate)
								{
									$prospectsUpdated++;
									echo "<br>Updated successfully";
								}
								else
								{
									echo "<br>Failed to update.";
								}

							}	
							else
							{
								// Insret booking
								$rsltUpdate=$objProspect->insertProspect($arrDBProspect);
								if($rsltUpdate)
								{
									$prospectsCreated++;
									echo "<br>Inserted successfully";
								}
								else
								{
									echo "<br>Failed to insert.";
								}
							}
						}
						else
						{
							echo "<br>Failed to search booking by Id";
						}
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

		
		// Update prospect flag in zoho
		$recordCount=0;
		$arrAllBooking=[];
		$rsltProspects=$objProspect->getProspectsWithFlag();
		if($rsltProspects)
		{
			if($rsltProspects->num_rows>0)
			{
				while ($rowProspect=$rsltProspects->fetch_assoc()) 
				{
					$arrProspectUpdate=[];
					$arrProspectUpdate['id']=$rowProspect['prospect_id'];
					$arrProspectUpdate['Send_To_Reporting_Api']="";

					$arrAllProspects[]=$arrProspectUpdate;
					$recordCount++;

					if($recordCount>=98)
					{
						$recordCount=0;
						$resp=$objZoho->bulkUpdateRecords("Prospects",$arrAllProspects);
						$arrAllProspects=[];
					}
				}
				if(count($arrAllProspects)>0)
				{
					$resp=$objZoho->bulkUpdateRecords("Prospects",$arrAllProspects);
					$arrAllProspects=[];
				}
			}
		}
		else
		{
			echo "<br>Error";
		}

		// Update prospect flag in database
		$rsltUpdate=$objProspect->updateAllProspectsFlag();
		if($rsltUpdate)
		{
			echo "<br>Successfully updated flag in db.";
		}
		else
		{
			echo "<br>Failed to update flag in db.";
		}
	}
}
catch(Exception $e)
{
	echo "<br>Exception";
	print_r($e);
}