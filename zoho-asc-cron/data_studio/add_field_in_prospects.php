<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
require_once __DIR__."/class/zoho_prospects.class.php";
require_once __DIR__."/class/zoho_season_dates.class.php";
require_once __DIR__."/class/zoho_notify_me_sports_state.class.php";
$objBooking=new ZOHO_PROSPECTS();
$objProspectNotifyMe=new ZOHO_NOTIFY_ME_SPORTS_STATE();
$objSeasonDates=new ZOHO_SEASON_DATES();
$rsltBookings=$objBooking->getAllProspects();

$rsltCurrentSeason=$objSeasonDates->getCurrentSeasonBookingStartDate();
if($rsltCurrentSeason->num_rows>0)
{
	$arrCurrentSeason=$rsltCurrentSeason->fetch_assoc();
	$currentSeason=$arrCurrentSeason['season'];
	$currentYear=$arrCurrentSeason['year'];
	$currentBookingStartDate=$arrCurrentSeason['booking_start_date'];
}

if($rsltBookings)
{
	while ($rowBooking=$rsltBookings->fetch_assoc())
	{
		$jsonBookingRaw=$rowBooking['raw_data'];
		$arrZohoProspect=json_decode($jsonBookingRaw,true);

		$arrDBProspect=[];
		if(isset($arrZohoProspect['Notify_Me_Date']))
		{
			
			$arrDBProspect['notify_me_date']=$arrZohoProspect['Notify_Me_Date'];
			if($arrZohoProspect["Notify_Me_Date"]!="")
			{
				$arrDBProspect["notify_me_date"]=$arrZohoProspect["Notify_Me_Date"];

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
								$arrNotifyMe['prospect_id_local']=$rowBooking['rec_id'];
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
									echo "Inserted successfully";
								}
								else{
									echo "Failed to insert";
								}
							}
							else{
								echo "Already added";
							}
						}
						else
						{
							echo "Query error";
						}
					}
				}
			}
			
		}
		$arrDBProspect['first_name']=$arrZohoProspect['First_Name'];
		$arrDBProspect['last_name']=$arrZohoProspect['Last_Name'];

		if(count($arrDBProspect)>0)
		{
			// $recId=$rowBooking['rec_id'];
			// $rsltUpdate=$objBooking->updateProspect($recId,$arrDBProspect);
			// if($rsltUpdate)
			// {
			// 	echo "Updated successfully.";
			// }
			// else
			// {
			// 	echo "Failed to update.";
			// }
		}
	}
}
else
{
	echo "Failed to get Bookings.";
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
?>