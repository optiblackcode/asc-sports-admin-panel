<?php
error_reporting(1);
ini_set("display_errors", true);
include "curl_zoho/class/zoho_methods.class.php";
include "data_studio/class/zoho_camps.class.php";
include "data_studio/class/zoho_participants.class.php";

$objZoho=new ZOHO_METHODS();
$objCamps=new ZOHO_CAMPS();
$objParticipants=new ZOHO_PARTICIPANTS();

if(isset($_REQUEST['camp_id']))
{
	$arrGroupsAgeWise=[];
	$arrAllParticipants=[];
	$arrAddedParticipants=[];
	$arrMultipleFirstNames=[];
	$campId=$_REQUEST['camp_id'];
	$rsltAllParticipants=$objParticipants->getParticipantsByZohoCampId($campId);
	if($rsltAllParticipants)
	{	
		$noOfChild=$rsltAllParticipants->num_rows;
		$groupSize=decide_groupsize($noOfChild);

		// echo "Participants: ".$noOfChild;
		// echo "<br>";
		// echo "Group Size: ".$groupSize;

		// family wise groups
		$arrFamily=[];
		$arrAddedFamilies=[];

		while($rowParticipant=$rsltAllParticipants->fetch_assoc())
		{

			$arrParticipant=[];
			$participantId=$rowParticipant['participant_id'];
			$age=$rowParticipant['age'];
			$playingExp="";
			$friends="";
			$family="";
			$school="";
			$booking="";
			$email="";
			$firstName="";
			$lastName="";

			// Participant raw data
			$arrRawData=json_decode($rowParticipant['raw_data'],true);
			$playingExp=$arrRawData['Playing_Experience'];
			$friends=$arrRawData['Friends_Attending'];
			if(is_array($arrRawData['Family']))
			{
				$family=$arrRawData['Family']['id'];
			}
			if(is_array($arrRawData['Booking_Id']))
			{
				$booking=$arrRawData['Booking_Id']['id'];
			}
			$school=$arrRawData['School'];
			$email=$arrRawData['Email'];
			$firstName=$arrRawData['First_Name'];
			$lastName=$arrRawData['Last_Name'];

			$arrParticipant['id']=$participantId;
			$arrParticipant['age']=$age;
			$arrParticipant['exp']=$playingExp;
			$arrParticipant['friends']=$friends;
			$arrParticipant['family']=$family;
			$arrParticipant['school']=$school;
			$arrParticipant['booking']=$booking;
			$arrParticipant['email']=$email;
			$arrParticipant['first_name']=$firstName;
			$arrParticipant['last_name']=$lastName;

			$arrAllParticipants[]=$arrParticipant;

			// Sort exp wise
			if($playingExp=="")
			{
				$playingExp=0;
			}
			else if($playingExp=="Beginner")
			{
				$playingExp=0;
			}
			else if($playingExp=="Moderate")
			{
				$playingExp=1;
			}
			else if($playingExp=="Accomplished")
			{
				$playingExp=2;
			}

			// Sort family wise
			if($family=="")
			{
				$family="NA";
			}
			$arrFamily[$family][]=$arrParticipant;

			$arrGroupsAgeWise[$age][$playingExp][]=$arrParticipant;
		}
	}
	ksort($arrGroupsAgeWise);

	foreach ($arrGroupsAgeWise as $key => $value) {
		ksort($arrGroupsAgeWise[$key]);
	}
	
	// Get which first names are multiple
	foreach ($arrAllParticipants as $key => $arrParticipant) {
		$count=0;
		$firstName=clearName($arrParticipant['first_name']);
		foreach ($arrAllParticipants as $key => $arrInnerParticipant) {
			$innerFirstName=clearName($arrInnerParticipant['first_name']);
			if($firstName==$innerFirstName)
			{
				$count++;
			}
		}

		if($count>1)
		{
			$arrMultipleFirstNames[]=$firstName;
		}
		$count=0;
	}

	// Make age wise groups
	$arrGroups=[];

	$counter=0;
	$arrSingleGroup=[];
	foreach ($arrGroupsAgeWise as $age => $arrAgeGroup) 
	{
		foreach ($arrAgeGroup as $age => $arrExpGroup) 
		{
			foreach ($arrExpGroup as $exp => $arrPart) 
			{
				if($counter>=$groupSize) 
				{
					$arrGroups[]=$arrSingleGroup;
					$arrSingleGroup=[];
					$counter=0;
				}

				// Check if multiple participants of same family
				$blFamilyAdded=false;
				$partFamilyId=$arrPart['family'];
				$participantId=$arrPart['id'];

				/*
				if(!in_array($participantId, $arrAddedParticipants))
				{
					if($partFamilyId!="")
					{
						
						if(isset($arrFamily[$partFamilyId]) && !empty($arrFamily[$partFamilyId]))
						{
							$familyParticipants=count($arrFamily[$partFamilyId]);
							if($familyParticipants>1)
							{
								foreach ($arrFamily[$partFamilyId] as $key => $value) {
									if(!in_array($value['id'], $arrAddedParticipants))
									{
										$arrSingleGroup[]=$value;
										$arrAddedParticipants[]=$value['id'];
										$counter++;
									}
								}
								$blFamilyAdded=true;
							}
						}
					}
				}
				*/

				// Check for friends
				if(!in_array($participantId, $arrAddedParticipants))
				{
					$arrFriends=getFriends($arrPart,$arrAllParticipants,$arrAddedParticipants);
					foreach ($arrFriends as $key => $arrParticipant) {
						if(!in_array($arrFriends['id'], $arrAddedParticipants))
						{
							$arrSingleGroup[]=$arrParticipant;
							$counter++;
						}
					}
				}
				

				// Check for friends
				if(!in_array($participantId, $arrAddedParticipants))
				{
					$counter++;
					$arrSingleGroup[]=$arrPart;
					$arrAddedParticipants[]=$arrPart['id'];
				}
			}
		}
	}
	if(count($arrSingleGroup)>0)
	{
		$arrGroups[]=$arrSingleGroup;
		$arrSingleGroup=[];
	}

	$strResponse=count($arrGroups)." groups created.";
	$arrZohoParticipants=[];
	foreach ($arrGroups as $groupNo => $arrGroup) 
	{	
		$groupName="Group-".($groupNo+1);
		//$strResponse.=$groupName.": ".count($arrGroup);

		foreach ($arrGroup as $key => $arrParticipant) 
		{
			$arrZohoParticipant=[];
			$arrZohoParticipant['id']=$arrParticipant['id'];
			$arrZohoParticipant['Group_Name']=$groupName;
			$arrZohoParticipants[]=$arrZohoParticipant;
		}
	}

	if($objZoho->checkTokens())
	{
		if(count($arrZohoParticipants)>0)
		{
			$arrTrigger=[];
			$arrParts=[];
			$arrParts=array_chunk($arrZohoParticipants,100);



			foreach ($arrParts as $arrUpdateParticipants) 
			{
				$rsltUpdateParticipant=$objZoho->bulkUpdateRecords("Participant",$arrUpdateParticipants,$arrTrigger);
				// echo "<pre>";
				// print_r($rsltUpdateParticipant);
			}
		}
	}
	echo $strResponse;
}
else
{

}

function decide_groupsize($noOfChild)
{

	// Check with 14
	$groupSize=14;
	$modulo=$noOfChild%$groupSize;
	if($modulo==0)
	{
		return $groupSize;
	}

	if($modulo<10)
	{
		$groupSize--;
		$modulo=$noOfChild%$groupSize;
		if($modulo==0)
		{
			return $groupSize;
		}

		if($modulo<10)
		{
			$groupSize--;
			$modulo=$noOfChild%$groupSize;
			if($modulo==0)
			{
				return $groupSize;
			}

			if($modulo<10)
			{
				$groupSize--;
				$modulo=$noOfChild%$groupSize;
				if($modulo==0)
				{
					return $groupSize;
				}
			}
		}

	}

	return $groupSize;
}

function clearName($name)
{
	$name=preg_replace("/[^A-Za-z\s]/", " ", $name);
	$name=trim($name);
	return $name;
}

function getFriends($arrPart,$arrAllParticipants,&$arrAddedParticipants,&$arrSingleGroup=[])
{	
	// Check for friends
	$participantFName=clearName($arrPart['first_name']);
	$participantLName=clearName($arrPart['last_name']);

	foreach ($arrAllParticipants as $key => $arrParticipant) 
	{

		if(!in_array($arrParticipant['id'], $arrAddedParticipants))
		{
			$friends=$arrParticipant['friends'];
			$blMatched=false;
			if(strpos($friends, $participantFName)!==false && strpos($friends, $participantLName)!==false)
			{
				$blMatched=true;
			}

			if($blMatched)
			{
				$arrAddedParticipants[]=$arrParticipant['id'];
				$arrSingleGroup[]=$arrParticipant;
				$arrFriends=getFriends($arrParticipant,$arrAllParticipants,$arrAddedParticipants,$arrSingleGroup);
				if(count($arrFriends)==0)
				{
					foreach ($arrFriends as $key => $value) {
						$arrSingleGroup[]=$value;
						$arrAddedParticipants[]=$value['id'];
					}
					return $arrSingleGroup;
				}
			}
		}

	}
	return $arrSingleGroup;
}
?>