
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
                <h3>Camp Group</h3>
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
				<form method="POST" action="camp_group_process.php" >
				<div class="content-wrapper">
					<div class="container-xxl flex-grow-1 container-p-y">
					<?php
					
						if(isset($_GET['success'])){
							echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			        </button>
			        <strong>Success!</strong> Your Camp Group successfully created.
			    </div>';
						}
					?>
						<div class="card mb-2">
							<h5 class="card-header">Camp Group</h5>
							<div class="card-body">
								
									<div class="col-lg-5">
										<div class="mb-3">
										  <label for="exampleFormControlSelect1" class="form-label">Venues</label>
										  <select class="form-select sele newVB_array" name="vanue" id="venue">
											<option selected="">Select Venues</option>
											<?php
												/*foreach($newVB_array as $vba){
													echo '<option value="'.$vba['id'].'">'.$vba['name'].'</option>';
												}*/
											?>
										  </select>
										</div>
									</div>
									<div class="col-lg-5">
										<div class="mb-3" >
										  <label for="exampleFormControlSelect1" class="form-label">Dates</label>
										  <select class="form-select selec camp_arr" name="date" id="date">
											<option selected="" value="" >Select Camp Dates</option>
											<?php
												/*foreach($camp_arr as $ca){
													if($ca['Camp_Dates'] != ""){
														echo '<option  value="'.$ca.'">'.$ca.'</option>';
													}
												}*/
											?>
										  </select>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="mb-3" style="margin-top: 25px;">
										 <input type="button" name="src-btn" id="src-btn" class="btn btn-success  " value="Find Camps"  />
										</div>
									</div>
									<div class="col-lg-12 camp_sec" style="display:none;" >
										<div class="table-responsive text-nowrap">
											<table class="table table-striped">
												<thead>
													<tr>
														<th>#</th>
														<th>No of Groups</th>
														<th>Camp Name</th>
														<th>Camp SKU</th>
														<th>State</th>
														<th>Suburb</th>
														<th>Camp date</th>
														
														<th>Sports</th>
														<th>No of Participants</th>
														
													</tr>
												</thead>
												<tbody class="table-border-bottom-0" id="Camp_response">
													
												</tbody>
											</table>
											
										</div>
										<input type="button" name="gen" id="gen" class="btn btn-success gen btn-sm" value="Generate Group"  />
									</div>
									</div>
								
							</div>
							<div class="card mb-3">
								<h5 class="card-header">Chief of Staff</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="cos">
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select HC_array" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														/*foreach($HC_array as $hca){
															echo '<option data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}*/
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option selected="">Select Status</option>
													<option value="1">Hired</option>
													<option value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
												</div>
												<div class="col-lg-6 ">
													<h5 class="card-header">Notes</h5>
													<textarea class="form-control" name="notes" ></textarea>
												</div>
												</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										</div>
										<input data-repeater-create id="addc" type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							<div class="card mb-3">
								<h5 class="card-header">First Aid</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="fa">
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select HC_array" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														/*foreach($HC_array as $hca){
															echo '<option data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}*/
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option selected="">Select Status</option>
													<option value="1">Hired</option>
													<option value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control" name="notes" ></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										</div>
										<input data-repeater-create id="addc" type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							<div class="card mb-3">
								<h5 class="card-header">Video</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="vid">
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select HC_array" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														/*foreach($HC_array as $hca){
															echo '<option data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].'  - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}*/
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option selected="">Select Status</option>
													<option value="1">Hired</option>
													<option value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control" name="notes" ></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										</div>
										<input data-repeater-create id="addc" type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							
							
							<div class="card mb-3">
								<h5 class="card-header">Coaching Director</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="cd">
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select HC_array" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														/*foreach($HC_array as $hca){
															echo '<option data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].'  - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}*/
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option selected="">Select Status</option>
													<option value="1">Hired</option>
													<option value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control" name="notes" ></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										</div>
										<input data-repeater-create id="addc" type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							
							<div class="card mb-3">
								<h5 class="card-header">Coaches</h5>
								<div class="card-body">
									<div class="repe">
										<div class="mainsec" id="mainsec" >
										<div class="row rep mb-3 clone" id="klone0" >
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select sele coach HC_array" name="coach[0][coach]" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														/*foreach($HC_array as $hca){
															echo '<option data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}*/
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coach[0][coachhired]" id="exampleFormControlSelect1">
													<option selected="">Select Status</option>
													<option value="1">Hired</option>
													<option value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="coach[0][total]" id="exampleFormControlInput1" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												
												</div>
												<div class="col-lg-6 coachdet">
											</div>
											<div class="col-lg-6 ">
												<h5 class="card-header">Notes</h5>
												<textarea class="form-control notes" name="coach[0][notes]" ></textarea>
											</div>
											</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="coach[0][day1avail]" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="coach[0][day1price]" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="coach[0][day2avail]" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="coach[0][day2price]" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="coach[0][day3avail]" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="coach[0][day3price]" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										</div>
										<input  id="addc" type="button" class="btn btn-success btn-sm addc" value="+"/>
										<input style="float: right;" type="button" name="genc" id="genc" class="btn btn-success genc btn-sm" value="Add coaches to group"  />
									</div>
								</div>	
							</div>
							<div class="camps_details" style="display:none;">
							
							</div>	
							
							<div class="card mt-3 mb-3">
								<h5 class="card-header">Talent</h5>
								<div class="card-body">
									<div class="repeate">
										<div class=""  data-repeater-list="tal">
										<div class="row rep mb-3 "  data-repeater-item>
											<div class="col-lg-12">
											<div class="row">
												<div class="sel">
													
												</div>
												<div class="mb-3 col-lg-5">
												  <select class="form-select select HC_array" name="coach" id="exampleFormControlSelect1">
													<option selected="">Select Staff</option>
													<?php
														/*foreach($HC_array as $hca){
															echo '<option data-id="'.$hca['State'].'" value="'.$hca['id'].'">'.$hca['name'].' - '.$hca['Primary_Phone'].' - '.$hca['State'].'</option>';
														}*/
													?>
												  </select>
												</div>
												<div class="mb-3 col-lg-3">
												  <select class="form-select ch" name="coachhired" id="exampleFormControlSelect1">
													<option selected="">Select Status</option>
													<option value="1">Hired</option>
													<option value="2">Unhired</option>
													<option value="0">Pending</option>
												  </select>
												</div>
												<div class="mb-3 col-lg-3 total">
												<input type="number" class="form-control ttl" name="total" id="exampleFormControlInput1" placeholder="Total Price">
												</div>
												<div class="mb-3 col-lg-1">
												<input data-repeater-delete style="float:right;" class="btn btn-danger btn-sm" type="button" value="-"/>
												</div>
												<div class="col-lg-6 coachdet">
												</div>
												<div class="col-lg-6 ">
													<h5 class="card-header">Notes</h5>
													<textarea class="form-control" name="notes" ></textarea>
												</div>
												</div>
											</div>
											<div class="row">
											<div class="col-lg-4">
												<h5 class="card-header">Day 1</h5>
												<div class="mb-1">
												  <select class="form-select day1" name="day1avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day1price" class="form-control day1price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 2</h5>
												<div class="mb-1">
												  <select class="form-select day2" name="day2avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day2price" class="form-control day2price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											<div class="col-lg-4">
												<h5 class="card-header">Day 3</h5>
												<div class="mb-1">
												  <select class="form-select day3"  name="day3avail" id="exampleFormControlSelect1">
													<option >Select Availability</option>
													<option selected="" value="1">Yes</option>
													<option value="0">No</option>
												  </select>
												</div>
												<div class="mb-1">
													<input type="number" name="day3price" class="form-control day3price" id="exampleFormControlInput1" placeholder="Price">
												</div>
											</div>
											</div>
											
										</div>
										</div>
										<input data-repeater-create id="addc" type="button" class="btn btn-success btn-sm" value="+"/>
										
									</div>
								</div>	
							</div>
							
							
							<button type="submit" class="btn btn-primary mt-3">Submit</button>	
						</div>  
						<input type="hidden" name="inc" id="inc" value="0" />
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
		
		let result = getQoutientsArray(800, 3);
let result3 = getQoutientsArray(1600, 6);
let result2 = getQoutientsArray(700, 4);



console.log(result);
console.log(result2)
console.log(result3)


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
		
		
		var ajax_url = 'ajax/api.php';
		$(document).on("change",".select,.coach",function() {
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
						//var newp = (hprice/3).toFixed(2);
						var result = getQoutientsArray(hprice, 3);
						_this.closest(".rep").find(".day1price").val(result[0]);
					_this.closest(".rep").find(".day2price").val(result[1]);
					_this.closest(".rep").find(".day3price").val(result[2]).trigger("change");
					}
					else{
						var newp = 0;
						_this.closest(".rep").find(".day1price").val(newp);
					_this.closest(".rep").find(".day2price").val(newp);
					_this.closest(".rep").find(".day3price").val(newp).trigger("change");
					}
					
					
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
		$('.selec').select2();
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
			$( "#klon"+num+" .ch" ).attr( "name", 'coach['+num+'][coachhired]' );
			$( "#klon"+num+" .ttl" ).attr( "name", 'coach['+num+'][total]');
			$( "#klon"+num+" .day1" ).attr( "name", 'coach['+num+'][day1avail]');
			$( "#klon"+num+" .day2" ).attr( "name", 'coach['+num+'][day2avail]');
			$( "#klon"+num+" .day3" ).attr( "name", 'coach['+num+'][day3avail]');
			$( "#klon"+num+" .day1price" ).attr( "name", 'coach['+num+'][day1price]');
			$( "#klon"+num+" .day2price" ).attr( "name", 'coach['+num+'][day2price]');
			$( "#klon"+num+" .day3price" ).attr( "name", 'coach['+num+'][day3price]');
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
						camphtml+='<h5>&nbsp;</h5>';
						camphtml+='<input type="text" class="form-control" name="grp'+j+'" id="exampleFormControlInput1" placeholder="Group Name" />';
						camphtml+='</div>';
						camphtml+='</div>';
						camphtml+='<div class="col-lg-9">';
						camphtml+='<div class="row">';
						camphtml+='<div class="col-lg-4">';
						camphtml+='<h5>Day 1</h5>';
						camphtml+='<select class="form-select coachsel sel'+v+'" multiple name="coach1'+j+'[]" id="sel'+v+'">';
						camphtml+='<option>Select Staff</option>';
						camphtml+='</select>';
						camphtml+='</div>';
						camphtml+='<div class="col-lg-4">';
						camphtml+='<h5>Day 2</h5>';
						camphtml+='<select class="form-select coachsel sel'+v+'" multiple name="coach2'+j+'[]" id="sel'+v+'">';
						camphtml+='<option>Select Staff</option>';
						camphtml+='</select>';
						camphtml+='</div>';
						camphtml+='<div class="col-lg-4">';
						camphtml+='<h5>Day 3</h5>';
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
			console.log("ID"+id);
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
					var op = "<option selected=''>Select Staff</option>";
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
		
    
    
    var ajaxurl1 = 'ajax/api.php';

    $.ajax({
        url: ajaxurl1,
        type: 'POST',
        data: {
            api: 'get_all_data',
        },
        dataType: 'JSON',
        success: function (response) {
            // Ensure response is parsed (if needed)
            if (typeof response === 'string') {
                response = JSON.parse(response);
            }

            console.log('Full Response:', response);

            // Handle newVB_array (object)
            if (typeof response.newVB_array === 'object' && !Array.isArray(response.newVB_array)) {
                var vbOptions = '<option selected="">Select Venues</option>';
                Object.values(response.newVB_array).forEach(function (item) {
                    vbOptions += '<option value="' + item.id + '">' + item.name + '</option>';
                });
                $('#venue').html(vbOptions);
            } else {
                console.error('newVB_array is not an object:', response.newVB_array);
            }

            // Handle HC_array (array)
            if (Array.isArray(response.HC_array)) {
                var hcOptions = '<option selected="">Select Staff</option>';
                response.HC_array.forEach(function (item) {
                    hcOptions += '<option data-id="' + item.State + '" value="' + item.id + '">' + item.name + ' - ' + item.Primary_Phone + ' - ' + item.State + '</option>';
                });
                $('.HC_array').html(hcOptions);
            } else {
                console.error('HC_array is not an array:', response.HC_array);
            }

            // Handle camp_arr (object)
            if (typeof response.camp_arr === 'object' && !Array.isArray(response.camp_arr)) {
                var campOptions = '<option selected="" value="">Select Camp Dates</option>';
                Object.values(response.camp_arr).forEach(function (item) {
                    if (item) {
                        campOptions += '<option value="' + item + '">' + item + '</option>';
                    }
                });
                $('#date').html(campOptions);
            } else {
                console.error('camp_arr is not an object:', response.camp_arr);
            }
        },
        error: function (error) {
            console.error('Error fetching data:', error);
        }
    });	
		
	});
</script>
	</body>
</html>