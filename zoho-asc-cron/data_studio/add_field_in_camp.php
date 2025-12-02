<?php
require __DIR__."/class/zoho_camps.class.php";
$objCamp=new ZOHO_CAMPS();
$rsltCamps=$objCamp->getAllCamps();

if($rsltCamps)
{
	while ($rowCamp=$rsltCamps->fetch_assoc())
	{
		$jsonCampRaw=$rowCamp['raw_data'];
		$arrCamp=json_decode($jsonCampRaw,true);

		$arrUpdatedCamp=[];
		$arrUpdatedCamp['venue_booked_id']=$arrCamp['Venue_Booked_Name']['id'];
		$arrUpdatedCamp['partner_booked_id']=$arrCamp['Partner_Booked_Name']['id'];

		$recId=$rowCamp['rec_id'];
		$rsltUpdate=$objCamp->updateCamp($recId,$arrUpdatedCamp);
		if($rsltUpdate)
		{
			echo "Updated successfully.";
		}
		else
		{
			echo "Failed to update.";
		}
	}
}
else
{
	echo "Failed to get camps.";
}
?>