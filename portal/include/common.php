<?php
session_start();
require_once __DIR__."/../../zoho-asc-cron/data_studio/class/autoload.php";
include __DIR__."/functions.php";
$objUser=new PORTAL_USER();

if(!isset($isLoginPage))
{
	$isLoginPage=false;
}

$isUserLoggedIn=$objUser->checkLogin();

if($isLoginPage)
{
	if($isUserLoggedIn)
	{
		header("Location:".$objUser->dashboardUrl);
		die();
	}
}
else
{
	if(!$isUserLoggedIn)
	{
		header("Location:".$objUser->loginPageUrl);
		die();	
	}
}

function getCategories()
{
	$arrCategories=[
		'event_email'=>'Email',
		'event_pricing'=>'Pricing',
		'event_sms'=>'SMS',
		'event_letterdraft'=>'Letterdrop',
		'event_facebook'=>'Facebook',
		'event_google'=>'Google',
		'event_eventbrite'=>'Eventbrite',
		'event_scoopon'=>'Scoopon',
		'event_team_app'=>'Team App',
		'event_website'=>'Website',
		'event_social_media'=>'Social Media',
		'event_flyer'=>'Flyers',
		'event_weather'=>'Weather',
		'event_scheduling'=>'Scheduling',
		'event_holidays'=>'Holidays',
		'event_other'=>'Other'
	];
	return $arrCategories;
}

function format_date($date,$format="d M,Y")
{
	$date=date($format,strtotime($date));
	return $date;
}
function format_date_time($date,$format="d M,Y h:i A")
{
	$date=date($format,strtotime($date));
	$date.=" (UTC)";
	return $date;
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

function getSeasonYearFromDate($date)
{
	$year=date("Y",strtotime($date));
	$month=date("m",strtotime($date));
	$season = "";
	if($month <= 5 && $month >= 3)
	{
		$season = "Autumn";
	}
	else if($month <= 8 && $month >= 6)
	{
		$season = "Winter";
	}
	else if($month <= 11 && $month >= 9)
	{
		$season = "Spring";
	}
	else
	{
		$season = "Summer";
		if($month<3)
		{
			$year--;
		}
	}

	$arrResponse=[];
	$arrResponse['season']=$season;
	$arrResponse['year']=$year;
	return $arrResponse;
}

function getSports(){// ADD NEW SPORT HERE - For Brochure
	$arrSports=[
	  'AFL'=>'AFL Football',
	  'Athletics'=>'Athletics',
	  'AFLW'=>'AFLW Football',
	  'Soccer'=>'Soccer',
	  'Hockey'=>'Hockey',
	  'Cricket'=>'Cricket',
	  'Golf'=>'Golf',
	  'Tennis'=>'Tennis',
	  'Rugby Union'=>'Rugby Union',
	  'Netball'=>'Netball',
	  'Bike & Kayak'=>'Bike & Kayak',
	  'Partner Soccer'=>'Partner Soccer',
	  'Parkour'=>'Parkour',
	  'Basketball'=>'Basketball',
	  'Baseball'=>'Baseball',
	  'Rock Climbing'=>'Rock Climbing',
	  'Horse Riding'=>'Horse Riding',
	  'Futsal'=>'Futsal',
	  'Badminton'=>'Badminton',
	  'Ice Skating'=>'Ice Skating',
	  'Rowing'=>'Rowing',
	  'Speed & Agility'=>'Speed & Agility',
	  'Gymnastics & Trampolining'=>'Gymnastics & Trampolining',
	  'Table Tennis'=>'Table Tennis',
	  'Sailing'=>'Sailing',
	  'Ten Pin Bowling'=>'Ten Pin Bowling',
	  'Volleyball'=>'Volleyball',
	  'Karate'=>'Karate',
	  'Esports'=>'Esports',
	  'Rugby League'=>'Rugby League',
	  'Rock Climbing & Indoor Surfing'=>'Rock Climbing & Indoor Surfing',
	  'Lawn Bowls'=>'Lawn Bowls',
	  'Touch Football' => 'Touch Football'
	];
	asort($arrSports);
	return $arrSports;
}

function getStates(){
	$arrStates=[
	  'ACT',
	  'NSW',
	  'QLD',
	  'SA',
	  'VIC',
	  'WA',
	  'TAS',
	  'test'
	];
	return $arrStates;
}

function getFormattedDate($strDate)
{
	$strFormatted="";
	preg_match_all("/[0-9]{1,2}(,|\s|&)/",$strDate,$arrDays);
	preg_match_all("/[a-z-A-Z]{2,12}/",$strDate,$arrMonth);
	preg_match_all("/[0-9]{4}/",$strDate,$arrYear);

	$arrDays=$arrDays[0];
	foreach ($arrDays as $key => $value)
	{
		$day=trim(trim(trim($value,","),"&"));
		$day=date("jS",strtotime("1970-01-".$day));
		$arrDays[$key]=$day;
	}

	$days=implode(", ", $arrDays);
	$pos = strrpos($days, ',');

	if($pos !== false)
    {
        $days = substr_replace($days, ' &', $pos, 1);
    }

	$month=$arrMonth[0][0];
	
	$strFormatted=$month." ".$days;
	return $strFormatted;
}
?>