<?php
require_once __DIR__."/class/execution_logs.class.php";
$objExecutionLog=new EXECUTION_LOGS();
$execusitonLogId=$objExecutionLog->log_id;

require_once __DIR__."/../curl_zoho/class/zoho_methods.class.php";
require_once __DIR__."/class/zoho_participants.class.php";
require_once __DIR__."/class/zoho_bookings.class.php";
require_once __DIR__."/class/zoho_camps.class.php";
require_once __DIR__."/class/zoho_venues_booked.class.php";

$objZoho=new ZOHO_METHODS();
$objParticipant=new ZOHO_PARTICIPANTS();
$objBooking=new ZOHO_BOOKINGS();
$objCamp=new ZOHO_CAMPS();
$objVB=new ZOHO_VENUES_BOOKED();
try
{
	if($objZoho->checkTokens())
	{
		/*
		echo "<h1>Camps</h1>";
		// ************ Get all camps and insert or update in database ***********
		$campsCreated=0;
		$campsUpdated=0;
		$moreRecord=true;
		$maxCount=500;
		$i=1;
		while($i<=$maxCount && $moreRecord)
		{
			// Get records from camps with flag 3108913000015403301
			$arrParams['cvid']="3108913000015403301";
			$arrParams['page']=$i;
			$arrParams['per_page']=200; 
			$resp=$objZoho->getRecords("Camps",$arrParams);
			if(isset($resp['data']))
			{
				if(count($resp['data'])>0)
				{
					foreach ($resp['data'] as $key => $arrZohoCamp) 
					{
						// Insert/Update booking in database
						$arrDBCamp=[];
						$arrDBCamp["camp_id"]=$arrZohoCamp["id"];
						$arrDBCamp["venue_booked_id"]=$arrZohoCamp["Venue_Booked_Name"]["id"];
						$arrDBCamp["partner_booked_id"]=$arrZohoCamp["Partner_Booked_Name"]["id"];
						$arrDBCamp["camp_name"]=$arrZohoCamp["Name"];
						$arrDBCamp["camp_group"]=$arrZohoCamp["Camp_Group_Name"]["name"];
						$arrDBCamp["camp_sku"]=$arrZohoCamp["SKU"];
						$arrDBCamp["season"]=$arrZohoCamp["Season"];
						$arrDBCamp["year"]=$arrZohoCamp["Year"];
						$arrDBCamp["sports"]=$arrZohoCamp["Sports"]["name"];
						$arrDBCamp["business_arm"]=$arrZohoCamp["Business_Arm"];
						$arrDBCamp["camp_start_date"]=$arrZohoCamp["Camp_Start_Date"];
						$arrDBCamp["camp_end_date"]=$arrZohoCamp["Camp_End_Date"];
						$arrDBCamp["booking_start_date"]=$arrZohoCamp["Booking_Start_Date"];
						$arrDBCamp["booking_end_date"]=$arrZohoCamp["Booking_End_Date"];
						$arrDBCamp["earlybird_start_date"]=$arrZohoCamp["Early_Bird_Start_date"];
						$arrDBCamp["earlybird_end_date"]=$arrZohoCamp["Early_Bird_End_Date"];
						if($arrZohoCamp["Is_Partner_Program"]=="Yes")
						{
							$arrDBCamp["is_partner"]=1;
						}
						else
						{
							$arrDBCamp["is_partner"]=0;
						}
						$arrDBCamp["capacity"]=$arrZohoCamp["Capacity"];
						$arrDBCamp["camp_suburb"]=$arrZohoCamp["Suburb"]["name"];
						$arrDBCamp["camp_city"]=$arrZohoCamp["City"];
						$arrDBCamp["camp_state"]=$arrZohoCamp["State"];
						$arrDBCamp["camp_country"]=$arrZohoCamp["Country"];
						$arrDBCamp["camp_postcode"]=$arrZohoCamp["Postcode"];
						$arrDBCamp["raw_data"]=json_encode($arrZohoCamp);
						$arrDBCamp["flag_complete"]='0';

						$rsltCamp=$objCamp->getCampByZohoId($arrDBCamp['camp_id']);
						if($rsltCamp)
						{
							if($rsltCamp->num_rows>0)
							{
								$rowFoundCamp=$rsltCamp->fetch_assoc();
								$recId=$rowFoundCamp['rec_id'];

								// Update booking
								$rsltUpdate=$objCamp->updateCamp($recId,$arrDBCamp);
								if($rsltUpdate)
								{
									$campsUpdated++;
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
								$rsltUpdate=$objCamp->insertCamp($arrDBCamp);
								if($rsltUpdate)
								{
									$campsCreated++;
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

		
		// Update camp flag in zoho
		$recordCount=0;
		$arrAllBooking=[];
		$rsltCamps=$objCamp->getCampsWithFlag();
		if($rsltCamps)
		{
			if($rsltCamps->num_rows>0)
			{
				while ($rowCamp=$rsltCamps->fetch_assoc()) 
				{
					$arrCampUpdate=[];
					$arrCampUpdate['id']=$rowCamp['camp_id'];
					$arrCampUpdate['Send_To_Reporting_Api']="";

					$arrAllCamps[]=$arrCampUpdate;
					$recordCount++;

					if($recordCount>=98)
					{
						$recordCount=0;
						$resp=$objZoho->bulkUpdateRecords("Camps",$arrAllCamps);
						$arrAllCamps=[];
					}
				}
				if(count($arrAllCamps)>0)
				{
					$resp=$objZoho->bulkUpdateRecords("Camps",$arrAllCamps);
					$arrAllCamps=[];
				}
			}
		}
		else
		{
			echo "<br>Error";
		}

		// Update camp flag in database
		$rsltUpdate=$objCamp->updateAllCampsFlag();
		if($rsltUpdate)
		{
			echo "<br>Successfully updated flag in db.";
		}
		else
		{
			echo "<br>Failed to update flag in db.";
		}
		*/
		/*
		echo "<h1>Participants</h1>";	
		// ************ Get all participants and insert or update in database ***********
		$participantsCreated=0;
		$participantsUpdated=0;
		$moreRecord=true;
		$maxCount=500;
		$i=1;
		while($i<=$maxCount && $moreRecord)
		{
			// Get records from participants with flag 3108913000015403125
			$arrParams['cvid']="3108913000015403125";
			$arrParams['page']=$i;
			$arrParams['per_page']=200; 
			$resp=$objZoho->getRecords("Participant",$arrParams);
			if(isset($resp['data']))
			{
				if(count($resp['data'])>0)
				{
					foreach ($resp['data'] as $key => $arrZohoParticipant) 
					{
						// Insert/Update participant in database
						$arrDBParticipant=[];
						$arrDBParticipant['participant_id']=$arrZohoParticipant['id'];
						$arrDBParticipant['booking_id']=$arrZohoParticipant['Booking_Id']['id'];
						$arrDBParticipant['camp_id']=$arrZohoParticipant['Camp_Name']['id'];
						$arrDBParticipant['child_id']=$arrZohoParticipant['Child_Name']['id'];
						$arrDBParticipant['booking_status']=$arrZohoParticipant['Booking_Status'];
						if($arrZohoParticipant['Early_Bird']=="")
						{
							$arrDBParticipant['is_earlybird']='0';
						}
						else
						{
							$arrDBParticipant['is_earlybird']=$arrZohoParticipant['Early_Bird'];
						}
						$arrDBParticipant['status']=$arrZohoParticipant['Status'];
						$arrDBParticipant['sub_total']=$arrZohoParticipant['Sub_Total'];
						$arrDBParticipant['total']=$arrZohoParticipant['Total'];
						$arrDBParticipant['discount']=$arrZohoParticipant['Discount'];
						$arrDBParticipant['stripe_fee']=$arrZohoParticipant['Stripe_Fee'];
						$arrDBParticipant['net_revenue']=$arrZohoParticipant['Net_Revenue'];
						$arrDBParticipant['net_revenue_manual']=$arrZohoParticipant['Net_Revenue_Manual'];
						$arrDBParticipant['camp_sku']=$arrZohoParticipant['SKU'];
						$arrDBParticipant['business_arm']=$arrZohoParticipant['Business_Arm'];
						$arrDBParticipant['dob']=$arrZohoParticipant['DOB'];
						$arrDBParticipant['age']=$arrZohoParticipant['Age'];
						$arrDBParticipant['gender']=$arrZohoParticipant['Gender'];
						$arrDBParticipant['booking_date']=$arrZohoParticipant['Booking_Date'];
						$arrDBParticipant['booking_date_time']=$arrZohoParticipant['Booking_Date_Time_A'];
						$arrDBParticipant['participant_type']=$arrZohoParticipant['Participant_Type'];
						$arrDBParticipant['sports']=$arrZohoParticipant['Sports_A'];
						$arrDBParticipant['season']=$arrZohoParticipant['Season'];
						$arrDBParticipant['year']=$arrZohoParticipant['Year'];
						$arrDBParticipant['day_of_season']=$arrZohoParticipant['Day_Of_Season'];
						$arrDBParticipant['week_of_season']=$arrZohoParticipant['Week_Of_Season'];
						$arrDBParticipant['camp_name']=$arrZohoParticipant['Camp_Name']['name'];
						$arrDBParticipant['utm_source']=$arrZohoParticipant['UTM_Source'];
						$arrDBParticipant['utm_medium']=$arrZohoParticipant['UTM_Medium'];
						$arrDBParticipant['utm_campaign']=$arrZohoParticipant['UTM_Campaign'];
						$arrDBParticipant['utm_term']=$arrZohoParticipant['UTM_Term'];
						$arrDBParticipant['utm_content']=$arrZohoParticipant['UTM_Content'];
						$arrDBParticipant['referer']=$arrZohoParticipant['Referer'];
						$arrDBParticipant['raw_data']=json_encode($arrZohoParticipant);
						$arrDBParticipant['flag_complete']='0';

						$rsltParticipants=$objParticipant->getParticipantByZohoId($arrDBParticipant['participant_id']);
						if($rsltParticipants)
						{
							if($rsltParticipants->num_rows>0)
							{
								$rowFoundParticipant=$rsltParticipants->fetch_assoc();
								$recId=$rowFoundParticipant['rec_id'];

								// Update Participant
								$rsltUpdate=$objParticipant->updateParticipant($recId,$arrDBParticipant);
								if($rsltUpdate)
								{
									$participantsUpdated++;
									echo "<br>Updated successfully";
								}
								else
								{
									echo "<br>Failed to update.";
								}

							}	
							else
							{
								// Insret participant
								$rsltUpdate=$objParticipant->insertParticipant($arrDBParticipant);
								if($rsltUpdate)
								{
									$participantsCreated++;
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
							echo "<br>Failed to search participant by Id";
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
		// Update participant flag in zoho
		$recordCount=0;
		$arrAllParticipant=[];
		$rsltParticipants=$objParticipant->getParticipantsWithFlag();
		if($rsltParticipants)
		{
			if($rsltParticipants->num_rows>0)
			{
				while ($rowParticipant=$rsltParticipants->fetch_assoc()) 
				{
					$arrParticipantUpdate=[];
					$arrParticipantUpdate['id']=$rowParticipant['participant_id'];
					$arrParticipantUpdate['Send_To_Reporting_Api']="";

					$arrAllParticipant[]=$arrParticipantUpdate;
					$recordCount++;

					if($recordCount>=98)
					{
						$recordCount=0;
						$resp=$objZoho->bulkUpdateRecords("Participant",$arrAllParticipant);
						$arrAllParticipant=[];
					}
				}
				if(count($arrAllParticipant)>0)
				{
					$resp=$objZoho->bulkUpdateRecords("Participant",$arrAllParticipant);
					$arrAllParticipant=[];
				}
			}
		}
		else
		{
			echo "<br>Error";
		}

		// Update participant flag in database
		$rsltUpdate=$objParticipant->updateAllParticipantFlag();
		if($rsltUpdate)
		{
			echo "<br>Successfully updated flag in db.";
		}
		else
		{
			echo "<br>Failed to update flag in db.";
		}
		
		*/
		/*
		echo "<h1>Bookings</h1>";
		// ************ Get all bookings and insert or update in database ***********
		$bookingsCreated=0;
		$bookingsUpdated=0;
		$moreRecord=true;
		$maxCount=500;
		$i=1;
		while($i<=$maxCount && $moreRecord)
		{
			// Get records from bookings with flag 3108913000015403065
			$arrParams['cvid']="3108913000015403065";
			$arrParams['page']=$i;
			$arrParams['per_page']=200; 
			$resp=$objZoho->getRecords("Bookings",$arrParams);
			if(isset($resp['data']))
			{
				if(count($resp['data'])>0)
				{
					foreach ($resp['data'] as $key => $arrZohoBooking) 
					{
						// Insert/Update booking in database
						$arrDBBooking=[];
						$arrDBBooking['booking_id']=$arrZohoBooking['id'];
						$arrDBBooking['order_id']=$arrZohoBooking['Order_Id'];
						$arrDBBooking['family_id']=$arrZohoBooking['Family_Id']['id'];
						$arrDBBooking['parent_id']=$arrZohoBooking['Parent_Guardian_Name']['id'];
						$arrDBBooking['booking_email']=$arrZohoBooking['Email'];
						$arrDBBooking['business_arm']=$arrZohoBooking['Business_Arm'];
						$arrDBBooking['cart_amount']=$arrZohoBooking['Cart_Amount'];
						$arrDBBooking['discount']=$arrZohoBooking['Discount'];
						$arrDBBooking['amount_payable']=$arrZohoBooking['Amount_Payable'];
						$arrDBBooking['booking_status']=$arrZohoBooking['Booking_Status'];
						$arrDBBooking['status']=$arrZohoBooking['Status'];
						$arrDBBooking['refund_amount']=$arrZohoBooking['Refund_Amount'];
						$arrDBBooking['final_amount']=$arrZohoBooking['Final_Amount'];
						$arrDBBooking['stripe_fees']=$arrZohoBooking['Stripe_Fees'];
						$arrDBBooking['net_revenue']=$arrZohoBooking['Net_Revenue'];
						$arrDBBooking['coupon_code']=$arrZohoBooking['Coupon_Code'];
						$arrDBBooking['learn_about_camp_through']=$arrZohoBooking['I_Learnt_About_Camp_Through'];
						$arrDBBooking['b_suburb']=$arrZohoBooking['B_Suburb'];
						$arrDBBooking['b_suburb_auto']=$arrZohoBooking['B_Suburb_A']['name'];
						$arrDBBooking['b_state']=$arrZohoBooking['B_State'];
						$arrDBBooking['b_state_auto']=$arrZohoBooking['B_State_A'];
						$arrDBBooking['b_city_auto']=$arrZohoBooking['B_City_A'];
						$arrDBBooking['raw_data']=json_encode($arrZohoBooking);
						$arrDBBooking['flag_complete']='0';

						$rsltBooking=$objBooking->getBookingByZohoId($arrDBBooking['booking_id']);
						if($rsltBooking)
						{
							if($rsltBooking->num_rows>0)
							{
								$rowFoundBooking=$rsltBooking->fetch_assoc();
								$recId=$rowFoundBooking['rec_id'];

								// Update booking
								$rsltUpdate=$objBooking->updateBooking($recId,$arrDBBooking);
								if($rsltUpdate)
								{
									$bookingsUpdated++;
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
								$rsltUpdate=$objBooking->insertBooking($arrDBBooking);
								if($rsltUpdate)
								{
									$bookingsCreated;
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
		*/
		/*
		// Update booking flag in zoho
		$recordCount=0;
		$arrAllBooking=[];
		$rsltBookings=$objBooking->getBookingsWithFlag();
		if($rsltBookings)
		{
			if($rsltBookings->num_rows>0)
			{
				while ($rowBooking=$rsltBookings->fetch_assoc()) 
				{
					$arrBookingUpdate=[];
					$arrBookingUpdate['id']=$rowBooking['booking_id'];
					$arrBookingUpdate['Send_To_Reporting_Api']="";

					$arrAllBooking[]=$arrBookingUpdate;
					$recordCount++;

					if($recordCount>=98)
					{
						$recordCount=0;
						$resp=$objZoho->bulkUpdateRecords("Bookings",$arrAllBooking);
						$arrAllBooking=[];
					}
				}
				if(count($arrAllBooking)>0)
				{
					$resp=$objZoho->bulkUpdateRecords("Bookings",$arrAllBooking);
					$arrAllBooking=[];
				}
			}
		}
		else
		{
			echo "<br>Error";
		}

		// Update Booking flag in database
		$rsltUpdate=$objBooking->updateAllBookingsFlag();
		if($rsltUpdate)
		{
			echo "<br>Successfully updated flag in db.";
		}
		else
		{
			echo "<br>Failed to update flag in db.";
		}
		*/
		
		echo "<h1>Venues Booked</h1>";
		// ************ Get all venues booked and insert or update in database ***********
		$vbCreated=0;
		$vbUpdated=0;
		$moreRecord=true;
		$maxCount=500;
		$i=1;
		while($i<=$maxCount && $moreRecord)
		{
			// Get records from venues booked with flag 3108913000015584021
			//$arrParams['cvid']="3108913000015584021";
			$arrParams['page']=$i;
			$arrParams['per_page']=200; 
			$resp=$objZoho->getRecords("Venues_Booked",$arrParams);
			if(isset($resp['data']))
			{
				if(count($resp['data'])>0)
				{
					foreach ($resp['data'] as $key => $arrZohoVB) 
					{
						// Insert/Update booking in database
						$arrDBVenuesBooked=[];
						$arrDBVenuesBooked["venues_booked_id"]=$arrZohoVB["id"];
						$arrDBVenuesBooked["venues_booked_name"]=$arrZohoVB["Name"];
						$arrDBVenuesBooked["venue_id"]=$arrZohoVB["Venue_Name"]['id'];
						$arrDBVenuesBooked["venue_name"]=$arrZohoVB["Venue_Name"]["name"];
						$arrDBVenuesBooked["season"]=$arrZohoVB["Season_A"];
						$arrDBVenuesBooked["year"]=$arrZohoVB["Year_A"];
						$arrDBVenuesBooked["commencement_date"]=$arrZohoVB["Commence_Date"];
						$arrDBVenuesBooked["end_date"]=$arrZohoVB["End_Date"];
						$arrDBVenuesBooked["milestone"]=$arrZohoVB["Milestone"];
						$arrDBVenuesBooked["status"]=$arrZohoVB["Status"];
						$arrDBVenuesBooked["business_arm"]=$arrZohoVB["Business_Arm"];
						$arrDBVenuesBooked["is_partner"]=$arrZohoVB["Is_Partner"];
						$arrDBVenuesBooked["no_of_camps"]=$arrZohoVB["No_of_Camps_A"];
						$arrDBVenuesBooked["cost_type"]=$arrZohoVB["Cost_Type"];
						$arrDBVenuesBooked["total_cost_ex_gst"]=$arrZohoVB["Total_Cost_Ex_GST_A"];
						$arrDBVenuesBooked["total_cost_inc_gst"]=$arrZohoVB["Total_Cost_Inc_GST_A"];
						$arrDBVenuesBooked["venue_suburb"]=$arrZohoVB["Suburb"]["name"];
						$arrDBVenuesBooked["venue_state"]=$arrZohoVB["State"];
						$arrDBVenuesBooked["venue_city"]=$arrZohoVB["City"];
						$arrDBVenuesBooked["venue_postcode"]=$arrZohoVB["Postcode"];
						$arrDBVenuesBooked["flag_complete"]="0";
						$arrDBVenuesBooked["raw_data"]=json_encode($arrZohoVB);

						$rsltVB=$objVB->getVBByZohoId($arrDBVenuesBooked['venues_booked_id']);
						if($rsltVB)
						{
							if($rsltVB->num_rows>0)
							{
								$rowFoundVB=$rsltVB->fetch_assoc();
								$recId=$rowFoundVB['rec_id'];

								// Update venues booked
								$rsltUpdate=$objVB->updateVB($recId,$arrDBVenuesBooked);
								if($rsltUpdate)
								{
									$vbUpdated++;
									echo "<br>Updated successfully";
								}
								else
								{
									echo "<br>Failed to update.";
								}

							}	
							else
							{
								// Insret venues booked
								$rsltUpdate=$objVB->insertVB($arrDBVenuesBooked);
								if($rsltUpdate)
								{
									$vbCreated++;
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
		/*
		// Update vb flag in zoho
		$recordCount=0;
		$arrAllVB=[];
		$rsltVB=$objVB->getVBWithFlag();
		if($rsltVB)
		{
			if($rsltVB->num_rows>0)
			{
				while ($rowVB=$rsltVB->fetch_assoc()) 
				{
					$arrBookingUpdate=[];
					$arrBookingUpdate['id']=$rowVB['venues_booked_id'];
					$arrBookingUpdate['Send_To_Reporting_Api']="";

					$arrAllVB[]=$arrBookingUpdate;
					$recordCount++;

					if($recordCount>=98)
					{
						$recordCount=0;
						$resp=$objZoho->bulkUpdateRecords("Venues_Booked",$arrAllVB);
						$arrAllVB=[];
					}
				}
				if(count($arrAllVB)>0)
				{
					$resp=$objZoho->bulkUpdateRecords("Venues_Booked",$arrAllVB);
					$arrAllVB=[];
				}
			}
		}
		else
		{
			echo "<br>Error";
		}

		// Update venues booked flag in database
		$rsltUpdate=$objVB->updateAllVBFlag();
		if($rsltUpdate)
		{
			echo "<br>Successfully updated flag in db.";
		}
		else
		{
			echo "<br>Failed to update flag in db.";
		}
		*/
		echo "<h3>Participant</h3>";
		echo "<br/>Inserted : ".$participantsCreated;
		echo "<br/>Updated : ".$participantsUpdated;
		echo "<h3>Bookings</h3>";
		echo "<br/>Inserted : ".$bookingsCreated;
		echo "<br/>Updated : ".$bookingsUpdated;
		echo "<h3>Camps</h3>";
		echo "<br/>Inserted : ".$campsCreated;
		echo "<br/>Updated : ".$campsUpdated;
		echo "<h3>Venues  Booked</h3>";
		echo "<br/>Inserted : ".$vbCreated;
		echo "<br/>Updated : ".$vbUpdated;
	}
	else
	{
		echo "<br>Issue with tokens.";
	}
}
catch(Exception $e)
{
	echo "<br>Exception";
	print_r($e);
}

?>