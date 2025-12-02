<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time',300);
ini_set('memory_limit', '-1');
require_once __DIR__."/class/zoho_participants.class.php";
require_once __DIR__."/class/zoho_season_dates.class.php";
require_once __DIR__."/class/zoho_master.class.php";

$objParticipant=new ZOHO_PARTICIPANTS();
$objSeasonDates=new ZOHO_SEASON_DATES();
$objZohoMaster=new ZOHO_MASTER();

$arrSeasonDates=$objSeasonDates->getAllBookingStartDates();
$participantsFailedToUpdate=0;
$participantsUpdated=0;
if(is_array($arrSeasonDates))
{
	$rsltAllParticipants=$objParticipant->getAllParticipants();
	if($rsltAllParticipants)
	{
		while ($rowParticipant=$rsltAllParticipants->fetch_assoc()) 
		{
			$seasonYear=$rowParticipant['camp_season']."_".$rowParticipant['camp_year'];
			if(isset($arrSeasonDates[$seasonYear]))
			{
				if($rowParticipant['booking_date_time']=="0000-00-00 00:00:00" || $rowParticipant['booking_date_time']=="")
				{
					$participantBookingDate=$rowParticipant['booking_date'];
				}
				else
				{
					$participantBookingDate=$rowParticipant['booking_date_time'];
				}
				$bookingStartDate=$arrSeasonDates[$seasonYear]['booking_start_date'];
				if($bookingStartDate!="0000-00-00" && $bookingStartDate!="" && $participantBookingDate!="0000-00-00" && $participantBookingDate!="")
				{
					$weekOfSeason=calculateWeek($bookingStartDate,$participantBookingDate);
					$dayOfSeason=calculateDays($bookingStartDate,$participantBookingDate);

					$arrUpdatedParticipant=[];
					$arrUpdatedParticipant['calculated_day_of_season']=$dayOfSeason;
					$arrUpdatedParticipant['calculated_week_of_season']=$weekOfSeason;

					$recId=$rowParticipant['rec_id'];
					$rsltUpdate=$objParticipant->updateParticipant($recId,$arrUpdatedParticipant);
					if($rsltUpdate)
					{
						$participantsUpdated++;
						echo "<br>Updated successfully.";
					}
					else
					{
						$participantsFailedToUpdate++;
						echo "<br>Failed to Update.";
					}
				}
			}
		}

		echo "Updated : ".$participantsUpdated;
		echo "<br>";
		echo "Failed : ".$participantsFailedToUpdate;
	}
	else
	{
		echo "Error in getting all participants.";
	}

	// Drop and recreate table
	if($objZohoMaster->dropTable())
	{
		echo "Table droped successfully.";
	}
	else
	{
		echo "Failed to drop the table.";
	}

	if($objZohoMaster->recreateTable())
	{
		echo "Table created successfully.";
	}
	else
	{
		echo "Failed to create the table.";
	}
}
else
{
	echo "Error in getting start dates.";
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