<?php
require __DIR__."/class/zoho_participants.class.php";
$objCamp=new ZOHO_PARTICIPANTS();
$rsltCamps=$objCamp->getAllParticipants();

if($rsltCamps)
{
	while ($rowCamp=$rsltCamps->fetch_assoc())
	{
		$jsonCampRaw=$rowCamp['raw_data'];
		$arrCamp=json_decode($jsonCampRaw,true);
		
		$arrUpdatedCamp=[];
		$arrUpdatedCamp['family_id']=$arrCamp['Family']['id'];

		$recId=$rowCamp['rec_id'];
		$rsltUpdate=$objCamp->updateParticipant($recId,$arrUpdatedCamp);
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