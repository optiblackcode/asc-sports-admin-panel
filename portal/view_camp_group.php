<?php
include "include/common.php";
require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      // Include common header for all pages 
      include "include/common_head.php";
    ?>
    <style type="text/css">
      .talent-image{
        max-width: 100px;
        max-height: 100px;
      }
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
                <h3>Camp Group View</h3>
              </div>

              <div class="title_right">
                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                  <div class="input-group">

                  </div>
                </div>
              </div>
            </div>

            <div class="clearfix"></div>
	  <?php
	  
		$host="localhost";
		$username="RBUH9jPnna";
		$password="BYdxh5JIu!";
		$db="asc_datastudio_reportingnew";
		$con = mysqli_connect("localhost",$username,$password,$db);
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
		$tal = $raw['tal'];
		$cd = $raw['cd'];
		$vid = $raw['vid'];
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

		function Get_Status($id){	
			$str = "";
			if($id == 1){
				$str = "Hired";
			}
			if($id == 2){
				$str = "Unhired";
			}
			return $str;
		}	
		
	  ?>
	  
	  
            <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="card mb-3">
						<div class="card-body">
							<h4>Venue</h4>
							<p><?php echo $venue_name; ?></p>
						</div>	
					</div>	
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
							<h4>Chief of Staff</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Coach Name</th>
										<th>Status</th>
										<th>Notes</th>
										<th>Total Price</th>
										<th>Day 1 Availability / Price</th>
										<th>Day 2 Availability / Price</th>
										<th>Day 3 Availability / Price</th>
									</tr>
								</thead>
								<tbody class="table-border-bottom-0">
									<?php
										foreach($cos as $c){
									?>
									<tr>
										<td><?php echo Get_Name($c['coach']); ?></td>
										<td><?php echo Get_Status($c['coachhired']); ?></td>
										<td><?php echo $c['notes']; ?></td>
										<td><?php echo $c['total']; ?></td>
										<td><?php echo ($c['day1avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day1price']; ?></td>
										<td><?php echo ($c['day2avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day2price']; ?></td>
										<td><?php echo ($c['day3avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day3price']; ?></td>
										
										
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
							<h4>First Aid</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Coach Name</th>
										<th>Status</th>
										<th>Notes</th>
										<th>Total Price</th>
										<th>Day 1 Availability / Price</th>
										<th>Day 2 Availability / Price</th>
										<th>Day 3 Availability / Price</th>
									</tr>
								</thead>
								<tbody class="table-border-bottom-0">
									<?php
										foreach($fa as $c){
											if($c['total'] != ''){	
									?>
									<tr>
										<td><?php echo Get_Name($c['coach']); ?></td>
										<td><?php echo Get_Status($c['coachhired']); ?></td>
										<td><?php echo $c['notes']; ?></td>
										<td><?php echo $c['total']; ?></td>
										<td><?php echo ($c['day1avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day1price']; ?></td>
										<td><?php echo ($c['day2avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day2price']; ?></td>
										<td><?php echo ($c['day3avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day3price']; ?></td>
										
										
									</tr>
									<?php
										}
										}
										
									?>
								</tbody>
							</table>
						</div>	
					</div>	
					<div class="card mb-3">
						<div class="card-body">
							<h4>Video</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Coach Name</th>
										<th>Status</th>
										<th>Notes</th>
										<th>Total Price</th>
										<th>Day 1 Availability / Price</th>
										<th>Day 2 Availability / Price</th>
										<th>Day 3 Availability / Price</th>
									</tr>
								</thead>
								<tbody class="table-border-bottom-0">
									<?php
										foreach($vid as $c){
											if($c['total'] != ''){	
									?>
									<tr>
										<td><?php echo Get_Name($c['coach']); ?></td>
										<td><?php echo Get_Status($c['coachhired']); ?></td>
										<td><?php echo $c['notes']; ?></td>
										<td><?php echo $c['total']; ?></td>
										<td><?php echo ($c['day1avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day1price']; ?></td>
										<td><?php echo ($c['day2avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day2price']; ?></td>
										<td><?php echo ($c['day3avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day3price']; ?></td>
										
										
									</tr>
									<?php
										}
										}
									?>
								</tbody>
							</table>
						</div>	
					</div>	
					
					
					<div class="card mb-3">
						<div class="card-body">	
							
							<h4>Coaching Director</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Coach Name</th>
										<th>Status</th>
										<th>Notes</th>
										<th>Total Price</th>
										<th>Day 1 Availability / Price</th>
										<th>Day 2 Availability / Price</th>
										<th>Day 3 Availability / Price</th>
									</tr>
								</thead>
								<tbody class="table-border-bottom-0">
									<?php
										foreach($cd as $c){
											if($c['total'] != ''){	
									?>
									<tr>
										<td><?php echo Get_Name($c['coach']); ?></td>
										<td><?php echo Get_Status($c['coachhired']); ?></td>
										<td><?php echo $c['notes']; ?></td>
										<td><?php echo $c['total']; ?></td>
										<td><?php echo ($c['day1avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day1price']; ?></td>
										<td><?php echo ($c['day2avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day2price']; ?></td>
										<td><?php echo ($c['day3avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day3price']; ?></td>
										
										
									</tr>
									<?php
										}
										}
									?>
								</tbody>
							</table>
							</div>	
					</div>	
					
					<div class="card mb-3">
						<div class="card-body">	
							<h4>Coaches</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Coach Name</th>
										<th>Status</th>
										<th>Notes</th>
										<th>Total Price</th>
										<th>Day 1 Availability / Price</th>
										<th>Day 2 Availability / Price</th>
										<th>Day 3 Availability / Price</th>
									</tr>
								</thead>
							<tbody class="table-border-bottom-0">
									<?php
										foreach($coach as $c){
											if($c['total'] != ''){	
											
									?>
									<tr>
										<td><?php echo Get_Name($c['coach']); ?></td>
										<td><?php echo Get_Status($c['coachhired']); ?></td>
										<td><?php echo $c['notes']; ?></td>
										<td><?php echo $c['total']; ?></td>
										<td><?php echo ($c['day1avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day1price']; ?></td>
										<td><?php echo ($c['day2avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day2price']; ?></td>
										<td><?php echo ($c['day3avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day3price']; ?></td>
										
										
									</tr>
									<?php
										}
										}
									?>
								</tbody>
							</table>
							</div>	
					</div>	
					<div class="card mb-3">
						<div class="card-body">	
							<h4>Talent</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th>Coach Name</th>
										<th>Status</th>
										<th>Notes</th>
										<th>Total Price</th>
										<th>Day 1 Availability / Price</th>
										<th>Day 2 Availability / Price</th>
										<th>Day 3 Availability / Price</th>
									</tr>
								</thead>
								<tbody class="table-border-bottom-0">
									<?php
									
										foreach($tal as $c){
											
										if($c['total'] != ''){	
									?>
									<tr>
										<td><?php echo Get_Name($c['coach']); ?></td>
										<td><?php echo Get_Status($c['coachhired']); ?></td>
										<td><?php echo $c['notes']; ?></td>
										<td><?php echo $c['total']; ?></td>
										<td><?php echo ($c['day1avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day1price']; ?></td>
										<td><?php echo ($c['day2avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day2price']; ?></td>
										<td><?php echo ($c['day3avail'] == 1) ? "Yes" : "No"; ?> / <?php echo $c['day3price']; ?></td>
										
										
									</tr>
									<?php
										}
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
								if($name != ''){
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
							} 
							?>
							
						</div>
					</div>
				</div>
            </div>
		</div>
    </div>
    <?php 
      // Include common footer for all pages
      include "include/common_footer.php";
    ?>
    
  </body>
</html>