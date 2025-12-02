<?php

$con = mysqli_connect("localhost","test","Admin@903324","asc_datastudio_reportingnew");


$sql = "SELECT * FROM `zoho_participants` WHERE `is_done` = 0 ORDER BY `rec_id` DESC LIMIT 200";
$result = mysqli_query($con, $sql);

echo mysqli_num_rows($result);

if(mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)) {
		$rec_id = $row['rec_id'];
		$participant_id = $row['participant_id'];
		$parti_id = "zcrm_".$row['participant_id'];
		$booking_id = $row['booking_id'];
		$book_id = "zcrm_".$row['booking_id'];
		$ca_id = $row['camp_id'];
		$camp_id = "zcrm_".$row['camp_id'];
		$child_id = "zcrm_".$row['child_id'];
		$family_id = "zcrm_".$row['family_id'];
		$booking_status = $row['booking_status'];
		$is_earlybird = $row['is_earlybird'];
		$status = $row['status'];
		$sub_total = $row['sub_total'];
		$total = $row['total'];
		$discount = $row['discount'];
		$stripe_fee = $row['stripe_fee'];
		$net_revenue = $row['net_revenue'];
		$net_revenue_manual = $row['net_revenue_manual'];
		$camp_sku = $row['camp_sku'];
		$business_arm = $row['business_arm'];
		$dob = $row['dob'];
		$age = $row['age'];
		$gender = $row['gender'];
		$booking_date = $row['booking_date'];
		$booking_date_time = $row['booking_date_time'];
		$participant_type = $row['participant_type'];
		$sports = $row['sports'];
		$season = $row['season'];
		$year = $row['year'];
		$day_of_season = $row['day_of_season'];
		$calculated_day_of_season = $row['calculated_day_of_season'];
		$week_of_season = $row['week_of_season'];
		$calculated_week_of_season = $row['calculated_week_of_season'];
		$camp_name = mysqli_real_escape_string($con,$row['camp_name']);
		$created_at = $row['created_at'];
		$modified_at = $row['modified_at'];
		
		
		$sql_fetch = mysqli_query($con,"SELECT * FROM `zoho_bookings` WHERE `booking_id` = '$booking_id'");
		$result_fetch = mysqli_query($con, $sql_fetch);
		while($fetch_row = mysqli_fetch_assoc($result_fetch)) {
			$b_suburb = $fetch_row['b_suburb'];
			$parent_type = $fetch_row['parent_type'];
			$family_type = $fetch_row['family_type'];
			$b_state = $fetch_row['b_state'];
		}
		
		$sql_fetch_2 = mysqli_query($con,"SELECT * FROM `zoho_camps` WHERE `camp_id` = '$ca_id'");
		$result_fetch_2 = mysqli_query($con, $sql_fetch_2);
		while($fetch_row_2 = mysqli_fetch_assoc($result_fetch_2)) {
			$camp_group = $fetch_row_2['camp_group'];
			$camp_suburb = $fetch_row_2['camp_suburb'];
			$camp_state = $fetch_row_2['camp_state'];
			$is_partner = $fetch_row_2['is_partner'];
		
		}
		
		
		
		 $sql_check = "SELECT * FROM `zoho_master_export` WHERE `booking_id` = '$book_id' AND `participant_id` = '$parti_id'";
		$result_check = mysqli_query($con, $sql_check);
		if(mysqli_num_rows($result_check) == 0){
			 $sql_insert = "INSERT INTO `zoho_master_export`( `participant_id`, `booking_id`, `camp_id`, `child_id`, `booking_family_id`, `participant_family_id`, `booking_status`, `is_earlybird`, `status`, `sub_total`, `total`, `discount`, `net_revenue`, `net_revenue_manual`, `camp_sku`, `business_arm`, `dob`, `age`, `gender`, `booking_date`, `booking_date_time`, `participant_type`, `is_partner`, `season`, `year`, `day_of_season`, `calculated_day_of_season`, `week_of_season`, `calculated_week_of_season`, `b_state`, `b_suburb`, `camp_name`, `camp_group`, `camp_suburb`, `camp_state`, `sports`, `venue_name`, `venue_booked_unique_id`, `camp_unique_id`, `parent_type`, `family_type`, `is_done`) VALUES ('$parti_id','$book_id','$camp_id','$child_id','$family_id','$family_id','$booking_status','$is_earlybird','$status','$sub_total','$total','$discount','$net_revenue','$net_revenue_manual','$camp_sku','$business_arm','$dob','$age','$gender','$booking_date','$booking_date_time','$participant_type','$is_partner','$season','$year','$day_of_season','$calculated_day_of_season','$week_of_season','$calculated_week_of_season','$b_state','$b_suburb','$camp_name','$camp_group','$camp_suburb','$camp_state','$sports','','','','$parent_type','$family_type','')";
			
			mysqli_query($con, $sql_insert)or die('Query failed: '. mysqli_error($con));
		}
		
		
		//Update
		$sql_up = mysqli_query($con,"UPDATE `zoho_participants` SET `is_done`= 1 WHERE `rec_id`=$rec_id");
	}
}


?>