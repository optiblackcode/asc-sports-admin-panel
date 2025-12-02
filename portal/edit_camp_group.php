<?php
include "include/common.php";

require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );
$VB_array = array();
$HC_array = array();
$host="localhost";
$username="RBUH9jPnna";
$password="BYdxh5JIu!";
$db="asc_datastudio_reportingnew";
$con = mysqli_connect("localhost",$username,$password,$db);



$objZoho=new ZOHO_METHODS();
try{	
	if($objZoho->checkTokens()){
		
		$cos = array();
		$cd = array();
		$fa = array();
		$j = 0;
		$o = 0;
		$d = 0;
		$f = 0;
		$qrySel ="SELECT * FROM `coaches`";
		$result = mysqli_query($con, $qrySel);
		while($rawdata = mysqli_fetch_assoc($result)){
			$HC_array[$j]['id'] =  $rawdata['ZOHOID'];
			$HC_array[$j]['name'] = $rawdata['name'];
			$HC_array[$j]['role'] = $rawdata['Role'];
			$HC_array[$j]['Primary_Phone'] = $rawdata['Primary_Phone'];
			$HC_array[$j]['State'] = $rawdata['State'];
			$j++;
		}
		
	}
}			
catch(Exception $e){
	$ResponseData = null;
}


$camp_arr = array_unique($camp_arr);


$date = date("Y-m-d H:i:s");
$id = $_GET['id'];
$qrySel="SELECT * FROM `camp_group` WHERE `ID` = '$id'";
$result = mysqli_query($con, $qrySel);
$data = mysqli_fetch_assoc($result);

$raw = json_decode($data['data'],true);
$venue = $raw['vanue'];
$camps = $raw['camps'];
$bulkcamp = $raw['bulkcamp'];
$cos = $raw['cos'];
$vid = $raw['vid'];
$tal = $raw['tal'];
$cd = $raw['cd'];
$fa = $raw['fa'];
$coach = $raw['coach'];
$inc = $raw['inc'];

$objZoho=new ZOHO_METHODS();
		try{	
			if($objZoho->checkTokens()){
				
				$arrvenue=$objZoho->getRecordById("Venues_Booked",$venue);
				if(count($arrvenue['data'])){
					$venue_name = $arrvenue['data'][0]['Name'];
				}
				
				$arrcamp=$objZoho->getRecordById("Camps",$camps);
				if(count($arrcamp['data'])){
					$Camp = $arrcamp['data'][0];
					$Name = $Camp['Name'];
					$SKU = $Camp['SKU'];
					$Camp_Dates = $Camp['Camp_Dates'];
					$State = $Camp['State'];
					$Suburb = (is_array($Camp['Suburb']) ? $Camp['Suburb']['name'] : "");
					$Sports = (is_array($Camp['Sports']) ? $Camp['Sports']['name'] : "");
				}
				$j = 0;
				$bulkcarr = array();
				foreach($bulkcamp as $bc){
					$arrbc=$objZoho->getRecordById("Camps",$bc);
					if(count($arrbc['data'])){
						$Camp = $arrbc['data'][0];
						$bulkcarr[$j]['Name'] =$Camp['Name'];
						$bulkcarr[$j]['SKU'] = $Camp['SKU'];
						$bulkcarr[$j]['Camp_Dates'] = $Camp['Camp_Dates'];
						$bulkcarr[$j]['State'] = $Camp['State'];
						$bulkcarr[$j]['Suburb'] = (is_array($Camp['Suburb']) ? $Camp['Suburb']['name'] : "");
						$bulkcarr[$j]['Sports'] = (is_array($Camp['Sports']) ? $Camp['Sports']['name'] : "");
						$j++;
					}
				}
				
			}
		}			
		catch(Exception $e){
			$ResponseData = null;
		}
		
		function Get_Name($id){
			
			$Name = "";
			$host="localhost";
			$username="RBUH9jPnna";
			$password="BYdxh5JIu!";
			$db="asc_datastudio_reportingnew";
			$conn = mysqli_connect("localhost",$username,$password,$db);
			$qrySel ="SELECT * FROM `coaches` WHERE `ZOHOID` = '$id'";
			$result = mysqli_query($conn, $qrySel);
			$count = mysqli_num_rows($result);
			if($count > 0){
				$rawdata = mysqli_fetch_assoc($result);
				$Name = $rawdata['name'];
			}
			
			return $Name;
		}

?>
<!DOCTYPE html>
<html lang="en">
	<head>
	    <?php
			// Include common header for all pages 
			include "include/common_head.php";
		?>
		<style>
	
	</style>
	</head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
			// Include common menu for all pages 
			include "include/common_main_menu.php";
		?>
		<div class="right_col" role="main" style="min-height: 1161px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Edit Camp Group</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">

                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
            
            <div class="row">
				<form method="POST" action="edit_camp_group_process.php" >
				
				
				<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="card mb-3">
						<div class="card-body">
							<h4>Venue</h4>
							<p><?php echo $venue_name; ?><input type="hidden" name="vanue" value="<?php echo $venue; ?>" /></p>
						</div>	
					</div>	
					<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<input type="hidden" name="camps" value="<?php echo $camps; ?>" />
					
					
					<?php
						foreach($bulkcamp as $bp){
					?>
					<input type="hidden" name="bulkcamp[]" value="<?php echo $bp; ?>" />
					<?php	
						}
					?>
					
					
					
					<div class="card mb-3">
						<div class="card-body">	
						<h4>Selected Camp</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Camp Name</th>
										<th>Camp SKU</th>
										<th>State</th>
										<th>Suburb</th>
										<th>Camp date</th>
										<th>Sports</th>
									</tr>
								</thead>
								<tbody class="table-border-bottom-0">
									<tr>
										<td><?php echo $Name; ?></td>
										<td><?php echo $SKU; ?></td>
										<td><?php echo $State; ?></td>
										<td><?php echo $Suburb; ?></td>
										<td><?php echo $Camp_Dates; ?></td>
										<td><?php echo $Sports; ?></td>
									</tr>
								</tbody>
							</table>
						</div>	
					</div>	
					<div class="card mb-3">
						<div class="card-body">	
							<h4>All Camps</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Camp Name</th>
										<th>Camp SKU</th>
										<th>State</th>
										<th>Suburb</th>
										<th>Camp date</th>
										<th>Sports</th>
									</tr>
								</thead>
								<tbody class="table-border-bottom-0" >
									<?php
										foreach($bulkcarr as $ba){
									?>
									<tr>
										<td><?php echo $ba['Name']; ?></td>
										<td><?php echo $ba['SKU']; ?></td>
										<td><?php echo $ba['State']; ?></td>
										<td><?php echo $ba['Suburb']; ?></td>
										<td><?php echo $ba['Camp_Dates']; ?></td>
										<td><?php echo $ba['Sports']; ?></td>
									</tr>
									<?php
										}
									?>
								</tbody>
							</table>
						</div>	
					</div>
					<div class="card mb-3">
						<div class="card-body">
							<h4>Groups</h4>
							
							<?php 
								for ($x = 0; $x <= $inc; $x++) {
									
								$name = $raw['grp'.$x];	
								$c1 = $raw['coach1'.$x];	
								$c2 = $raw['coach2'.$x];	
								$c3 = $raw['coach3'.$x];	
							?>
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Group Name</th>
											<th>Day 1 Coaches</th>
											<th>Day 2 Coaches</th>
											<th>Day 3 Coaches</th>
										</tr>
									</thead>
									<tbody class="table-border-bottom-0">
										<tr>
											<td><?php echo $name; ?></td>
											<td>
												<?php 
													foreach($c1 as $c){
														echo Get_Name($c).","; 
													}
													
												?>
											</td>
											<td>
												<?php 
													foreach($c2 as $c){
														echo Get_Name($c).","; 
													}
													
												?>
											</td>
											<td>
												<?php 
													foreach($c3 as $c){
														echo Get_Name($c).","; 
													}
													
												?>
											</td>
										</tr>
									</tbody>
								</table>
							<?php
								} 
							?>
							
						</div>
					</div>	
					</div>	
					</div>	
				
				
				
				
				
				
				
				<div class="content-wrapper">
					<div class="container-xxl flex-grow-1 container-p-y">
					<div class="card mb-3">
								<h5 class="card-header">Chief of Staff</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="cos">
										<?php
											foreach($cos as $t){
												
										?>
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														foreach($HC_array as $hca){
															$str = "";
															if($hca['id'] == $t['coach']){
																$str='selected="selected"';
															}
															echo '<option '.$str.' data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option value="">Select Status</option>
													<option <?php if($t['coachhired'] == 1){ echo 'selected="selected"';} ?> value="1">Hired</option>
													<option <?php if($t['coachhired'] == 2){ echo 'selected="selected"';} ?> value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" value="<?php echo $t['total']; ?>" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
												</div>
												<div class="col-lg-6 ">
													<h5 class="card-header">Notes</h5>
													<textarea class="form-control" name="notes" ><?php echo $t['notes']; ?></textarea>
												</div>
												</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day1avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day1avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" value="<?php echo $t['day1price']; ?>" name="day1price" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day2avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day2avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" value="<?php echo $t['day2price']; ?>" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day3avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day3avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" value="<?php echo $t['day3price']; ?>" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										
										<?php
											}
										?>
										</div>
										<input data-repeater-create  type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
						<div class="card mb-3">
								<h5 class="card-header">First Aid</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="fa">
										
										<?php
											foreach($fa as $t){
												
										?>
										
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														foreach($HC_array as $hca){
															$str = "";
															if($hca['id'] == $t['coach']){
																$str='selected="selected"';
															}
															echo '<option '.$str.' data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option value="">Select Status</option>
													<option <?php if($t['coachhired'] == 1){ echo 'selected="selected"';} ?> value="1">Hired</option>
													<option <?php if($t['coachhired'] == 2){ echo 'selected="selected"';} ?> value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" value="<?php echo $t['total']; ?>" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control" name="notes" ><?php echo $t['notes']; ?></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day1avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day1avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" value="<?php echo $t['day1price']; ?>" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day2avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day2avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" value="<?php echo $t['day2price']; ?>" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day3avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day3avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" value="<?php echo $t['day3price']; ?>" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										
										<?php
											}
										?>
										
										</div>
										<input data-repeater-create  type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							
							<div class="card mb-3">
								<h5 class="card-header">Video</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="vid">
										
										<?php
											foreach($vid as $t){
												
										?>
										
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														foreach($HC_array as $hca){
															$str = "";
															if($hca['id'] == $t['coach']){
																$str='selected="selected"';
															}
															echo '<option '.$str.' data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option value="">Select Status</option>
													<option <?php if($t['coachhired'] == 1){ echo 'selected="selected"';} ?> value="1">Hired</option>
													<option <?php if($t['coachhired'] == 2){ echo 'selected="selected"';} ?> value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" value="<?php echo $t['total']; ?>" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control" name="notes" ><?php echo $t['notes']; ?></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day1avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day1avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" value="<?php echo $t['day1price']; ?>" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day2avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day2avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" value="<?php echo $t['day2price']; ?>" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day3avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day3avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" value="<?php echo $t['day3price']; ?>" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										
										<?php
											}
										?>
										
										</div>
										<input data-repeater-create  type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							
							
							<div class="card mb-3">
								<h5 class="card-header">Coaching Director</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="cd">
										<?php
											foreach($cd as $t){
												
										?>
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														foreach($HC_array as $hca){
															$str = "";
															if($hca['id'] == $t['coach']){
																$str='selected="selected"';
															}
															echo '<option '.$str.' data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].'  - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option value="">Select Status</option>
													<option <?php if($t['coachhired'] == 1){ echo 'selected="selected"';} ?> value="1">Hired</option>
													<option <?php if($t['coachhired'] == 2){ echo 'selected="selected"';} ?> value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" value="<?php echo $t['total']; ?>" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control" name="notes" ><?php echo $t['notes']; ?></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day1avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day1avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" value="<?php echo $t['day1price']; ?>" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day2avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day2avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" value="<?php echo $t['day2price']; ?>" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day3avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day3avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" value="<?php echo $t['day3price']; ?>" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										
										<?php
											}
										?>
										
										</div>
										<input data-repeater-create  type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							
							
							
							
							<div class="card mb-3">
								<h5 class="card-header">Coaches</h5>
								<div class="card-body">
									<div class="repe">
										<div class="mainsec" id="mainsec" >
										<?php
											$K = 0;
											foreach($coach as $t){
												
												
										?>
										<div class="row rep mb-3 clone" id="klon<?php echo $K ?>" >
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select sele coach" name="coach[<?php echo $K ?>][coach]" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														foreach($HC_array as $hca){
															$str = "";
															if($hca['id'] == $t['coach']){
																$str='selected="selected"';
															}
															echo '<option '.$str.'  data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coach[<?php echo $K ?>][coachhired]" id="exampleFormControlSelect1">
													<option value="">Select Status</option>
													<option <?php if($t['coachhired'] == 1){ echo 'selected="selected"';} ?> value="1">Hired</option>
													<option <?php if($t['coachhired'] == 2){ echo 'selected="selected"';} ?> value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" value="<?php echo $t['total']; ?>" name="coach[<?php echo $K ?>][total]" id="exampleFormControlInput1" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control notes" name="coach[<?php echo $K ?>][notes]" ><?php echo $t['notes']; ?></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="coach[<?php echo $K ?>][day1avail]" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day1avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day1avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" value="<?php echo $t['day1price']; ?>" name="coach[<?php echo $K ?>][day1price]" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="coach[<?php echo $K ?>][day2avail]" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day2avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day2avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" value="<?php echo $t['day2price']; ?>" name="coach[<?php echo $K ?>][day2price]" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="coach[<?php echo $K ?>][day3avail]" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day3avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day3avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="coach[<?php echo $K ?>][day3price]" value="<?php echo $t['day3price']; ?>" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										<?php
											$K++;
											}
										?>
										</div>
										<input  id="addc" type="button" class="btn btn-success btn-sm addc" value="+"/>
										<input style="float: right;" type="button" name="genc" id="genc" class="btn btn-success genc btn-sm" value="Add coaches to group"  />
									</div>
								</div>	
							</div>
							
							<div class="card mb-3">
								<h5 class="card-header">Talent</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="tal">
										
										<?php
											foreach($tal as $t){
												
										?>
										
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														foreach($HC_array as $hca){
															$str = "";
															if($hca['id'] == $t['coach']){
																$str='selected="selected"';
															}
															echo '<option '.$str.' data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option value="">Select Status</option>
													<option <?php if($t['coachhired'] == 1){ echo 'selected="selected"';} ?> value="1">Hired</option>
													<option <?php if($t['coachhired'] == 2){ echo 'selected="selected"';} ?> value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" value="<?php echo $t['total']; ?>" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
												</div>
												<div class="col-lg-6 ">
													<h5 class="card-header">Notes</h5>
													<textarea class="form-control" name="notes" ><?php echo $t['notes']; ?></textarea>
												</div>
												</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day1avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day1avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" value="<?php echo $t['day1price']; ?>" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day2avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day2avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" value="<?php echo $t['day2price']; ?>" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option value="" >Select Availability</option>
													<option <?php if($t['day3avail'] == 1){ echo 'selected="selected"';} ?> value="1">Yes</option>
													<option <?php if($t['day3avail'] == 0){ echo 'selected="selected"';} ?> value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" value="<?php echo $t['day3price']; ?>" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										<?php
											}
										?>
										</div>
										<input data-repeater-create  type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							
							<div class="camps_details" >
								
			 <div class="card mt-3" data-id="'+v+'" >
						<h5 class="card-header"> <?php echo $Name; ?> <small>( <?php echo $Sports; ?> )</small>
						
						</h5>
						
						
						<div class="card-body b'+v+'">
						<?php
							for ($x = 0; $x <= $inc; $x++) {
								if($raw['grp'.$x] != ""){
							$name = $raw['grp'.$x];	
							$c1 = $raw['coach1'.$x];	
							$c2 = $raw['coach2'.$x];	
							$c3 = $raw['coach3'.$x];	
						?>
			
						<div class="row mb-3 r'+v+'">
						<div class="col-lg-3">
						<div class="mb-3">
						
						<input type="text" class="form-control" name="grp<?php echo $x; ?>" value="<?php echo $name; ?>" id="exampleFormControlInput1" placeholder="Group Name" />
						</div>
						</div>
						<div class="col-lg-9">
						<div class="row">
						<div class="col-lg-4">
						<select class="form-select coachsel sel" multiple name="coach1<?php echo $x; ?>[]" >
						<option value="" >Select Staff</option>
							
								<?php 
									foreach($c1 as $c){
										echo "<option selected='' value='".$c."' >" .Get_Name($c)."</option>"; 
									}
									
								?>
							
						</select>
						</div>
						<div class="col-lg-4">
						<select class="form-select coachsel sel" multiple name="coach2<?php echo $x; ?>[]" >
						<option value="" >Select Staff</option>
						<?php 
							foreach($c2 as $c){
								echo "<option selected='' value='".$c."' >" .Get_Name($c)."</option>"; 
							}
							
						?>
						</select>
						</div>
						<div class="col-lg-4">
						<select class="form-select coachsel sel" multiple name="coach3<?php echo $x; ?>[]" >
						<option value="" >Select Staff</option>
						<?php 
							foreach($c3 as $c){
								echo "<option selected='' value='".$c."' >" .Get_Name($c)."</option>"; 
							}
							
						?>
						</select>
						</div>
						</div>
						</div>
						</div>
						<?php
							}
							}
						?>
						
			
			</div>
						</div>
						
						
						
							</div>	
							<button type="submit" class="btn btn-primary mt-3">Submit</button>	
						</div>  
						<input type="hidden" name="inc" id="inc" value="<?php echo $inc; ?>" />
					</div>
					</form>
            </div>
          </div>
        </div>
		<?php 
			// Include common footer for all pages
			include "include/common_footer.php";
		?>
		<script type="text/javascript">
	$(document).ready(function() {
		function getQoutientsArray(num, divisor) {
		  let answers = [];

		  for (x = 0; x < divisor; x++) {
			let sum = answers.reduce((a, b) => a + b, 0);
			let qoutient = Math.round(num / divisor);

			let remaining = divisor - answers.length;
			let remaininganswer = (remaining-1) * Math.floor(num / divisor);

			if (sum + qoutient + remaininganswer <= num) {
			  answers.push(qoutient);
			} else {
			  answers.push(Math.floor(num / divisor));
			}
		  }

		  return answers;
		}

		$(document).on("change",".ttl",function() {
			var _this = $(this);
			var  d1a = _this.closest(".rep").find(".day1").val();
			var  d2a = _this.closest(".rep").find(".day2").val();
			var  d3a = _this.closest(".rep").find(".day3").val();
			if(d1a == 1 && d2a == 1 && d3a == 1){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/3);
					var result = getQoutientsArray(_this.val(), 3);
					
					var d1 = _this.closest(".rep").find(".day1price").val(result[0]);
					var d2 = _this.closest(".rep").find(".day2price").val(result[1]);
					var d3 = _this.closest(".rep").find(".day3price").val(result[2]);
				}
			}
			if(d1a == 1 && d2a == 1 && d3a == 0){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/3);
					var result = getQoutientsArray(_this.val(), 2);
					var d1 = _this.closest(".rep").find(".day1price").val(result[0]);
					var d2 = _this.closest(".rep").find(".day2price").val(result[1]);
					var d3 = _this.closest(".rep").find(".day3price").val(0);
				}
			}
			if(d1a == 1 && d2a == 0 && d3a == 1){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/2);
					var result = getQoutientsArray(_this.val(), 2);
					var d1 = _this.closest(".rep").find(".day1price").val(result[0]);
					var d2 = _this.closest(".rep").find(".day2price").val(0);
					var d3 = _this.closest(".rep").find(".day3price").val(result[1]);
				}
			}
			if(d1a == 0 && d2a == 1 && d3a == 1){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/2);
					var result = getQoutientsArray(_this.val(), 2);
					var d1 = _this.closest(".rep").find(".day1price").val(0);
					var d2 = _this.closest(".rep").find(".day2price").val(result[0]);
					var d3 = _this.closest(".rep").find(".day3price").val(result[1]);
				}
			}
			if(d1a == 0 && d2a == 0 && d3a == 0){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/3);
					var result = getQoutientsArray(_this.val(), 3);
					var d1 = _this.closest(".rep").find(".day1price").val(0);
					var d2 = _this.closest(".rep").find(".day2price").val(0);
					var d3 = _this.closest(".rep").find(".day3price").val(0);
				}
			}
			if(d1a == 1 && d2a == 0 && d3a == 0){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/3);
					var result = getQoutientsArray(_this.val(), 3);
					var d1 = _this.closest(".rep").find(".day1price").val(_this.val().toFixed(2));
					var d2 = _this.closest(".rep").find(".day2price").val(0);
					var d3 = _this.closest(".rep").find(".day3price").val(0);
				}
			}
			if(d1a == 0 && d2a == 1 && d3a == 0){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/1);
					var result = getQoutientsArray(_this.val(), 3);
					var d1 = _this.closest(".rep").find(".day1price").val(0);
					var d2 = _this.closest(".rep").find(".day2price").val(_this.val().toFixed(2));
					var d3 = _this.closest(".rep").find(".day3price").val(0);
				}
			}
			if(d1a == 0 && d2a == 0 && d3a == 1){
				if(_this.val() !=""){
					var val = parseFloat(_this.val()/3);
					var result = getQoutientsArray(_this.val(), 3);
					var d1 = _this.closest(".rep").find(".day1price").val(0);
					var d2 = _this.closest(".rep").find(".day2price").val(0);
					var d3 = _this.closest(".rep").find(".day3price").val(_this.val().toFixed(2));
				}
			}
			
			
		});
		$(document).on("change",".day1price,.day2price,.day3price,.day1,.day2,.day3",function() {
			var _this = $(this);
			var d1 = _this.closest(".rep").find(".day1price").val();
			var d2 = _this.closest(".rep").find(".day2price").val();
			var d3 = _this.closest(".rep").find(".day3price").val();
			
			var  d1a = _this.closest(".rep").find(".day1").val();
			var  d2a = _this.closest(".rep").find(".day2").val();
			var  d3a = _this.closest(".rep").find(".day3").val();
			
			if(d1a == 1 && d2a == 1 && d3a == 1){
				var sum= (parseFloat((d1 != "") ? d1 : 0)+parseFloat((d2 != "") ? d2 : 0)+parseFloat((d3 != "") ? d3 : 0));
			}
			if(d1a == 1 && d2a == 1 && d3a == 0){
				var sum= (parseFloat((d1 != "") ? d1 : 0)+parseFloat((d2 != "") ? d2 : 0));
			}
			if(d1a == 1 && d2a == 0 && d3a == 1){
				var sum= (parseFloat((d1 != "") ? d1 : 0)+parseFloat((d3 != "") ? d3 : 0));
			}
			if(d1a == 0 && d2a == 1 && d3a == 1){
				var sum= (parseFloat((d2 != "") ? d2 : 0)+parseFloat((d3 != "") ? d3 : 0));
			}
			if(d1a == 0 && d2a == 0 && d3a == 0){
				var sum= 0;
			}
			
			if(d1a == 1 && d2a == 0 && d3a == 0){
				var sum= (parseFloat((d1 != "") ? d1 : 0));
			}
			if(d1a == 0 && d2a == 1 && d3a == 0){
				var sum= (parseFloat((d2 != "") ? d2 : 0));
			}
			if(d1a == 0 && d2a == 0 && d3a == 1){
				var sum= (parseFloat((d3 != "") ? d3 : 0));
			}
			
			console.log(sum);
			_this.closest(".rep").find(".ttl").val(Math.round(sum).toFixed(2));
			
		});	
		
		
		$('.coachsel').select2();
		
		var ajax_url = 'ajax/api.php';
		$(document).on("change",".select,.coach,.ch",function() {
			var val = $(this).val();
			var _this = $(this);
			
			$.ajax({
				url: ajax_url,
				type: "POST",
				data:{
					api: 'get_coach_wwcc',
					id: val,
				},
				dataType: 'JSON',
				success:function(response){
					var resp = response.Data;
					
					var hprice = (resp.Hired_Price == 0) ? 0 : resp.Hired_Price;
					
					if(hprice != 0){
						var newp = (hprice/3).toFixed(2);
					}
					else{
						var newp = 0;
					}
					
					_this.closest(".rep").find(".day1price").val(newp);
					_this.closest(".rep").find(".day2price").val(newp);
					_this.closest(".rep").find(".day3price").val(newp).trigger("change");
					var html = '';
					html+='<table class="table table-striped">';
					html+='<tr>';
					html+='<th>Previous Hire Price</th>';
					html+='<th>WWCC</th>';
					html+='<th>WWC card_No</th>';
					html+='</tr>';
					html+='<tr>';
					html+='<td>'+((resp.Hired_Price == 0) ? ("-") : ("$"+resp.Hired_Price) )+'</td>';
					html+='<td>'+((resp.Working_With_Children_Check == null) ? ("-") : resp.Working_With_Children_Check) +'</td>';
					html+='<td>'+((resp.Working_with_children_card_No == null) ? ("-") : resp.Working_with_children_card_No) +'</td>';
					
					html+='</tr>';
					html+='</table>';
					_this.closest(".row").find(".coachdet").html(html);
					_this.closest(".clone").find(".coachdet").html(html);
					//$(this).parents(".clone").find(".coachdet").html(html)
					
				},
				error: function(errorThrown){
				   alert('error');
				}
			});
		});	
		
		
		$('.select').select2();
		$('.sele').select2();
		$('.mainsec .sele').select2();
		
		$('.addc').click(function() {
			console.log("here");
			var $div = $('div[id^="klon"]:last');
			var num = parseInt( $div.prop("id").match(/\d+/g), 10 ) +1;
			var $klon = $div.clone().prop('id', 'klon'+num );
			$(".mainsec").append($klon);
			$( ".coach" ).last().attr( "name", 'coach['+num+'][coach]');
			$( ".notes" ).last().attr( "name", 'coach['+num+'][notes]');
			$( "#klon"+num+" .ch" ).last().attr( "name", 'coach['+num+'][coachhired]' );
			$( "#klon"+num+" .ttl" ).last().attr( "name", 'coach['+num+'][total]');
			$( "#klon"+num+" .day1" ).last().attr( "name", 'coach['+num+'][day1avail]');
			$( "#klon"+num+" .day2" ).last().attr( "name", 'coach['+num+'][day2avail]');
			$( "#klon"+num+" .day3" ).last().attr( "name", 'coach['+num+'][day3avail]');
			$( "#klon"+num+" .day1price" ).last().attr( "name", 'coach['+num+'][day1price]');
			$( "#klon"+num+" .day2price" ).last().attr( "name", 'coach['+num+'][day2price]');
			$( "#klon"+num+" .day3price" ).last().attr( "name", 'coach['+num+'][day3price]');
			 $('.mainsec .select2-container').remove();
			  $('.sele').select2({
			  allowClear: true
			});
			$('.mainsec .select2-container').css('width','100%');
		});
		$('.genc').click(function() {
			var flg = '';
			var option = '';
			$('.coach').each(function(i, obj) {
				var v = $(this).val();
				var selected = $(this).find(":selected");
				if(v == ''){
					flg = 1;
				}
				else{
					option += '<option value="'+v+'">'+selected.text()+'</option>';
				}
				
			});
			if(flg == 1){
				alert("please select coach.");
				return false;
			}
			else{
				$('.coachsel').each(function(i, obj) {
					$(this).html(option);
					
					setTimeout(function() {
    console.log('first 10 secs');
    // loadCars();
  initselect3();
    

}, 2000);
					
				});	
				
			}
			
			
			
		});	
		function initselect3(){
			$('.coachsel').select2();
		}
		$('.gen').click(function() {
			$(".camps_details").hide();
			cnt = $('input.camp_chk:checked').length;
			if(cnt == 0){
				alert("please select camp");
				return false;
			}
			var v = $('input.camp_chk:checked').val();
			console.log(v);
			var vn = $('input.camp_chk:checked').attr('data-name');
			var vs = $('input.camp_chk:checked').attr('data-sport');
			
			var g = $('#grp'+v).val();
			if(g == ''){
				alert("please select Group");
				return false;
			}
			$('#inc').val(g);
			
			var camphtml = '';
			 camphtml+='<div class="card mt-3" data-id="'+v+'" >';
						camphtml+='<h5 class="card-header">'+vn+' <small>('+vs+')</small>';
						
						camphtml+='</h5>';
						camphtml+='<div class="card-body b'+v+'">';
						var j = 0;
			for (let i = 1; i <= g; i++) {
			
						camphtml+='<div class="row mb-3 r'+v+'">';
						camphtml+='<div class="col-lg-3">';
						camphtml+='<div class="mb-3">';
						
						camphtml+='<input type="text" class="form-control" name="grp'+j+'" id="exampleFormControlInput1" placeholder="Group Name" />';
						camphtml+='</div>';
						camphtml+='</div>';
						camphtml+='<div class="col-lg-9">';
						camphtml+='<div class="row">';
						camphtml+='<div class="col-lg-4">';
						camphtml+='<select class="form-select coachsel sel'+v+'" multiple name="coach1'+j+'[]" id="sel'+v+'">';
						camphtml+='<option>Select Staff</option>';
						camphtml+='</select>';
						camphtml+='</div>';
						camphtml+='<div class="col-lg-4">';
						camphtml+='<select class="form-select coachsel sel'+v+'" multiple name="coach2'+j+'[]" id="sel'+v+'">';
						camphtml+='<option>Select Staff</option>';
						camphtml+='</select>';
						camphtml+='</div>';
						camphtml+='<div class="col-lg-4">';
						camphtml+='<select class="form-select coachsel sel'+v+'" multiple name="coach3'+j+'[]" id="sel'+v+'">';
						camphtml+='<option>Select Staff</option>';
						camphtml+='</select>';
						camphtml+='</div>';
						camphtml+='</div>';
						camphtml+='</div>';
						camphtml+='</div>';
						 j++;
						
			} 
			camphtml+='</div>';
						camphtml+='</div>';
			$(".camps_details").html(camphtml);
			$(".camps_details").show();
			alert("Group generated Successfully");
		});
		var ajaxurl = 'ajax/api.php';
		$('#venue').on('change', function() {
			var id = $(this).val();
			var name = $("#venue option:selected").text();
			$.ajax({
				url: ajaxurl,
				type: "POST",
				data:{
					api: 'get_dates',
					id: id,
					name: name
				},
				dataType: 'JSON',
				success:function(response){
					var resp = response.Data;
					var html = '';
					$.each(resp, function (key, val) {
						html+='<option value="'+resp[key].Camp_Dates+'" >'+resp[key].Camp_Dates+'</option>';
					});
					
					$('#date').html(html);
					
				}
			});				
		});
		
		$('#src-btn').on('click', function() {
			var id = $('#venue').val();
			var date = $("#date").val();
			
			
			
			var name = $("#venue option:selected").text();
			$(".camp_sec").hide();
			$(".camps_details").hide();
			$.ajax({
				url: ajaxurl,
				type: "POST",
				data:{
					api: 'get_camps',
					id: id,
					name: name,
					date: date,
				},
				dataType: 'JSON',
				success:function(response){
					var resp = response.Data.Camp;
					var respc = response.Data.Coaches;
					var html = '';
					var camphtml = '';
					$.each(resp, function (key, val) {
						html+='<tr data-id="'+resp[key].id+'">';
						html+='<td><input type="radio" class="camp_chk" data-sport="'+resp[key].Sports+'" data-name="'+resp[key].Name+'" value="'+resp[key].id+'" name="camps" /><input type="hidden" name="bulkcamp[]" value="'+resp[key].id+'"/></td>';
						html+='<td><input type="number" name="grp"  id="grp'+resp[key].id+'" class="form-control grp" /></td>';
						html+='<td>'+resp[key].Name+'</td>';
						html+='<td>'+resp[key].SKU+'</td>';
						html+='<td>'+resp[key].state+'</td>';
						html+='<td>'+resp[key].Suburb+'</td>';
						html+='<td>'+resp[key].Camp_Dates+'</td>';
						
						html+='<td>'+resp[key].Sports+'</td>';
						html+='<td>'+resp[key].nop+'</td>';
						
						html+='</tr>';
						
						
						
					});
					var op = "";
					$.each(respc, function (key, val) {
						op+='<option value="'+respc[key].id+'" >'+respc[key].Name+' ('+respc[key].role+') - '+respc[key].Primary_Phone+'</option>';
					});
					$(".coach").html(op);
					$("#Camp_response").html(html);
					
					
					$.each(resp, function (key, val) {
						console.log("here");
						//$('#sel'+resp[key].id).select2({ width: '100%' });
					});
					$(".camp_sec").show();
					
				},
				error: function(errorThrown){
				   alert('error');
				}
			});
		});
		
		$(document).on("click",".btn-add",function() {
			var id = $(this).attr('data-id');
			if ($('.sel'+id).data('select2')) {
				$('.sel'+id).select2("destroy").select2({ width: '100%' });
			}
			else{
				$('.sel'+id).select2({ width: '100%' });
			}
			
			var $section = $(".r"+id+":first").clone(); 
			$('.b'+id).append($section); 
		});
		$(document).on("click",".btn-remove",function() {
			var id = $(this).attr('data-id');
			 $(this).parent('.row').remove();
		});
		
		var $repeater = $('.repeate').repeater({
			show: function () {
				$(this).slideDown();
				$('.repeate .select2-container').remove();
				  $('.select').select2({
				  allowClear: true
				});
				$('.repeate .select2-container').css('width','100%');
			},
			hide: function (deleteElement) {
				if(confirm('Are you sure you want to delete this coach?')) {
					$(this).slideUp(deleteElement);
				}
			},        
		});
		
		
	});
</script>
	</body>
</html>