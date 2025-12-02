<?php
require_once __DIR__."/class/zoho_bookings.class.php";
$objBooking=new ZOHO_BOOKINGS();
$rsltBookings=$objBooking->getAllBookings();

if($rsltBookings)
{
	while ($rowBooking=$rsltBookings->fetch_assoc())
	{
		$jsonBookingRaw=$rowBooking['raw_data'];
		$arrBooking=json_decode($jsonBookingRaw,true);

		$arrUpdatedBooking=[];
		$arrUpdatedBooking['parent_type']=$arrBooking['Customer_Type'];
		$arrUpdatedBooking['family_type']=$arrBooking['Family_Type'];

		$recId=$rowBooking['rec_id'];
		$rsltUpdate=$objBooking->updateBooking($recId,$arrUpdatedBooking);
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
	echo "Failed to get Bookings.";
}
?>