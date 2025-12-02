<?php
include "include/common.php";
$objCamps=new ZOHO_CAMPS();
$objVB=new ZOHO_VENUES_BOOKED();

$status=0;
$msg="";
$arrData=[];
if(isset($_GET['camp_id']) && !empty($_GET['camp_id']))
{
	$campId=$_GET['camp_id'];
	$rsltCamp=$objCamps->getCampById($campId);
	if($rsltCamp)
	{
		if($rowCamp=$rsltCamp->fetch_assoc())
		{
			
			$venueBookedId=$rowCamp['venue_booked_id'];
			$state=$rowCamp['camp_state'];
			$sports=$rowCamp['sports'];
			if($sports=="AFL Football" || $sports=="AFL/AFLW Football")
			{
				$sports="AFL";
			}

			$isPartner=$rowCamp['is_partner'];
			$suburb=$rowCamp['camp_suburb'];
			$arrSuburb=explode(",", $suburb);
			$suburb=trim($arrSuburb[0]);

			$arrRawData=json_decode($rowCamp['raw_data'],true);
			$campDates=$arrRawData['Camp_Dates'];
			$campDates=getFormattedDate($campDates);
			$venueName=$arrRawData['Camp_Venue'];

			// Get venue booked record
			$venueAddress="";
			if($venueBookedId!="")
			{
				$rsltVB=$objVB->getVBByZohoId($venueBookedId);
				if($rsltVB)
				{
					if($rowVB=$rsltVB->fetch_assoc())
					{
						$arrVBRawData=json_decode($rowVB['raw_data'],true);
						if(isset($arrVBRawData['Street_P_O_Box']) && !empty($arrVBRawData['Street_P_O_Box']))
						{
							$venueAddress=trim($arrVBRawData['Street_P_O_Box']);
						}
						if(isset($arrVBRawData['D_Street_2_A']) && $arrVBRawData['D_Street_2_A']!="")
						{
							$venueAddress.=",\n".$arrVBRawData['D_Street_2_A'];
						}
						$venueAddress.=",\n".$rowVB['venue_suburb'].".";
					}
				}
			}

			$status=1;
			$arrData['state']=$state;
			$arrData['sports']=$sports;
			$arrData['is_partner']=$isPartner;
			$arrData['suburb']=$suburb;
			$arrData['dates']=$campDates;
			$arrData['venue_name']=$venueName;
			$arrData['venue_address']=$venueAddress;
		}
	}		
}

$arrResponse=[];
$arrResponse['status']=$status;
$arrResponse['msg']=$msg;
$arrResponse['data']=$arrData;
echo json_encode($arrResponse);
?>