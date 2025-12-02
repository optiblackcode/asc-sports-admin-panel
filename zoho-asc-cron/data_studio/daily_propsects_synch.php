<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time',300);

require_once __DIR__."/class/execution_logs.class.php";
$objExecutionLog=new EXECUTION_LOGS();
$execusitonLogId=$objExecutionLog->log_id;

require_once __DIR__."/../curl_zoho/class/zoho_methods.class.php";
require_once __DIR__."/class/zoho_prospects.class.php";
require_once __DIR__."/class/zoho_season_dates.class.php";
require_once __DIR__."/class/zoho_notify_me_sports_state.class.php";

$objZoho=new ZOHO_METHODS();
$objProspect=new ZOHO_PROSPECTS();
$objProspectNotifyMe=new ZOHO_NOTIFY_ME_SPORTS_STATE();
$objSeasonDates=new ZOHO_SEASON_DATES();
$rsltCurrentSeason=$objSeasonDates->getCurrentSeasonBookingStartDate();
if($rsltCurrentSeason->num_rows>0)
{
	$arrCurrentSeason=$rsltCurrentSeason->fetch_assoc();
	$currentSeason=$arrCurrentSeason['season'];
	$currentYear=$arrCurrentSeason['year'];
	$currentBookingStartDate=$arrCurrentSeason['booking_start_date'];
	
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
							$arrDBProspect["first_name"]=$arrZohoProspect["First_Name"];
							$arrDBProspect["last_name"]=$arrZohoProspect["Last_Name"];
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
							$prospectRecId="";
							if($rsltProspect)
							{
								if($rsltProspect->num_rows>0)
								{
									$rowFoundProspect=$rsltProspect->fetch_assoc();
									$recId=$rowFoundProspect['rec_id'];
									$prospectRecId=$recId;
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
									$prospectRecId=$objProspect->insertProspect($arrDBProspect);
									if($prospectRecId)
									{
										$prospectsCreated++;
										echo "<br>Inserted successfully";
									}
									else
									{
										echo "<br>Failed to insert.";
									}
								}

								// Add Notify Me
								if($arrZohoProspect["Notify_Me_Date"]!="")
								{
									// Check with other table and add sports and state
									$strSportsState=$arrZohoProspect["Notify_Me_Sports_States"];
									if($strSportsState!=""){
										$arrExploded=explode("|", $strSportsState);
										foreach ($arrExploded as $key => $strSingleSportState) {
											$strSingleSportState=str_replace("{", "", $strSingleSportState);
											$strSingleSportState=str_replace("}", "", $strSingleSportState);
											$arrSportState=explode(":", $strSingleSportState);
											
											$sport="";
											$state="";
											$prospectId=$arrZohoProspect["id"];
											$sport=$arrSportState[0];
											$state=$arrSportState[1];

											if($sport==""){
												$sport="NS";
											}
											if($state==""){
												$state="NS";
											}

											//
											$rsltGetPropsectRecords=$objProspectNotifyMe->getRecordsByProspectId($prospectId);
											if($rsltGetPropsectRecords)
											{
												$notifyMeId="";
												if($rsltGetPropsectRecords->num_rows!=0){
													while ($rowGetPropsectRecords=$rsltGetPropsectRecords->fetch_assoc()) {
														if($rowGetPropsectRecords['sports']==$sport && $rowGetPropsectRecords['state']==$state){
															$notifyMeId=$rowGetPropsectRecords['rec_id'];
														}
													}
												}

												//
												if($notifyMeId==""){
													//Find day and week
													$dayOfSeason="";
													$weekOfSeason="";
													$weekOfSeason=calculateWeek($currentBookingStartDate,$arrZohoProspect["Notify_Me_Date"]);
													$dayOfSeason=calculateDays($currentBookingStartDate,$arrZohoProspect["Notify_Me_Date"]);

													$arrNotifyMe=[];
													$arrNotifyMe['prospect_id']=$prospectId;
													$arrNotifyMe['prospect_id_local']=$prospectRecId;
													$arrNotifyMe["first_name"]=$arrZohoProspect["First_Name"];
													$arrNotifyMe["last_name"]=$arrZohoProspect["Last_Name"];
													$arrNotifyMe['notify_me_date']=$arrZohoProspect["Notify_Me_Date"];
													$arrNotifyMe['sports_state']=$strSingleSportState;
													$arrNotifyMe['sports']=$sport;
													$arrNotifyMe['state']=$state;
													$arrNotifyMe['season']=$currentSeason;
													$arrNotifyMe['year']=$currentYear;
													$arrNotifyMe['day_of_season']=$dayOfSeason;
													$arrNotifyMe['week_of_season']=$weekOfSeason;

													// Insert record
													$rslt=$objProspectNotifyMe->insertProspect($arrNotifyMe);
													if($rslt){
														echo "<br>Notify me Inserted successfully";
													}
													else{
														echo "<br>Failed to insert";
													}
												}
											}
										}
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
}
function calculateWeek($startDate,$endDate)
{
	// Decide week 1 end date
	$week=0;
	$week1EndDate="";
	$startDay=date("w",strtotime($startDate));
	if($startDay>5)
	{
		$daysToAdd=(7-($startDay-5));
	}
	else
	{
		$daysToAdd=5-$startDay;
	}
	$week1EndDate=date("Y-m-d",strtotime($startDate." + ".$daysToAdd." days"));
	$week1EndDate=$week1EndDate." 16:00:00";

	// Calculate week
	$tsWeek1EndDate=strtotime($week1EndDate);
	$tsStartDate=strtotime($startDate);
	$tsEndDate=strtotime($endDate);
	if($tsEndDate<=$tsWeek1EndDate)
	{
		$week=1;
	}
	else
	{
		$diffOfDays=($tsEndDate-$tsWeek1EndDate)/(60 * 60 * 24);
		$week=ceil($diffOfDays/7);
		$week++;
	}
	return $week;
}

function calculateDays($startDate,$endDate)
{
	$endDate=date("Y-m-d",strtotime($endDate));
	$tsStartDate=strtotime($startDate);
	$tsEndDate=strtotime($endDate);

	$diffOfDays=ceil(($tsEndDate-$tsStartDate)/(60 * 60 * 24))+1;
	return $diffOfDays;
}