<?php
include "include/common.php";

require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );
$VB_array = array();
$HC_array = array();


$error = '';
$success = '';
if(isset($_POST['fn'])){

	$fname = $_POST['fn'];
	$lname = $_POST['ln'];
	$name = $fname.' '.$lname;
	$email = $_POST['e'];
	$countryCode = $_POST['cc'];
	$mobileNumber = $_POST['ph'];
	$Role = $_POST['Role'];
	$state = $_POST['state'];
	$Type = 0;
	
	if(!preg_match('/^[0-9]{10}+$/', $mobileNumber)){
		$error = "Please provide a valid mobile number";
	}
	
	$objZoho=new ZOHO_METHODS();
	try{	
		if($objZoho->checkTokens()){
			
			$criteria="(Email:equals:".$email.")";
			$arrParams['criteria']=$criteria;
			$Camps=$objZoho->searchRecords("Coaches",$arrParams);
			if(!count($Camps['data'])){
				$arrZohoCoach=[
					"Name"=>$name,
					"First_Name"=>$fname,
					"Middle_Name"=>"",
					"Last_Name"=>$lname,
					"Email"=> $email,
					"State"=> $state,
					"Role"=> array($Role),
					'Business_Arm'=>'NSC',
					'Primary_Phone'=>$mobileNumber,
					'Secondary_Phone'=>$mobileNumber,
					'Status_Flag'=>'Yes',
					'Flag_Message'=> 'Check phone number.',
					'Form_Id'=>'12'
				];
				$arrCoachInsert=[];
				$arrCoachInsert[]=$arrZohoCoach;
				$resultInsertCoach=$objZoho->insertRecord("Coaches",$arrCoachInsert);
				
				
				if($resultInsertCoach){
					if($resultInsertCoach['data'][0]['code']=="SUCCESS"){
						
						$success = "Coach data inserted successfully.";
						
					}
				}
			}
			
		}
	}			
	catch(Exception $e){
		$ResponseData = null;
	}
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
				
				<div class="content-wrapper">
					<div class="container-xxl flex-grow-1 container-p-y">
					<?php
					
						if($success !=""){
							echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			        </button>
			        <strong>Success!</strong> '.$success.'
			    </div>';
						}
					?>
					
												<div class="card">
						<h5 class="card-header">Add Coach</h5>
						<div class="card-body">
							<div class="table-responsive text-nowrap">
								<form class="col-lg-6" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
									<div class="mb-3">
										<label class="form-label" for="basic-default-fullname">First Name</label>
										<input type="text" class="form-control" name="fn" value="<?php echo ($_POST['fn'] != "") ?$_POST['fn'] : "";  ?>"  placeholder="First Name" required />
									</div>
									<div class="mb-3">
										<label class="form-label" for="basic-default-fullname">Last Name</label>
										<input type="text" class="form-control" name="ln" value="<?php echo ($_POST['ln'] != "") ?$_POST['ln'] : "";  ?>"  placeholder="Last Name" required />
									</div>
									<div class="mb-3">
										<label class="form-label" for="basic-default-fullname">Email</label>
										<input type="email" class="form-control" name="e" value="<?php echo ($_POST['e'] != "") ?$_POST['e'] : "";  ?>"  placeholder="Email" required />
									</div>
									<div class="mb-3">
										<label class="form-label" for="basic-default-fullname">Role</label>
										
										<select class="form-control" name="Role" required>
											<option  value="Assistant">Assistant</option>
											<option  value="Bowling Machine">Bowling Machine</option>
											<option  value="Chief of Staff">Chief of Staff</option>
											<option  value="Coaching">Coaching</option>
											<option  value="Coaching Director">Coaching Director</option>
											<option  value="Equipment">Equipment</option>
											<option  value="First Aid">First Aid</option>
											<option  value="Fitness">Fitness</option>
											<option  value="Group Coach">Group Coach</option>
											<option  value="Residential">Residential</option>
											<option  value="Senior Coach">Senior Coach</option>
											<option  value="Specialist Coach">Specialist Coach</option>
											<option  value="Talent">Talent</option>
											<option  value="Venue Co-ordinating">Venue Co-ordinating</option>
											<option  value="Video">Video</option>
											
										</select>
									</div>
									<div class="mb-3">
										<label class="form-label" for="basic-default-fullname">State</label>
										
										
										<select class="form-control" name="state" id="input_12_21_4" tabindex="1013" aria-required="true"><option value="ACT" selected="selected">Australian Capital Territory</option><option value="NT">Northern Territory</option><option value="NSW">New South Wales</option><option value="QLD">Queensland</option><option value="SA">South Australia</option><option value="TAS">Tasmania</option><option value="VIC">Victoria</option><option value="WA">Western Australia</option></select>
									</div>
									<div class="mb-3">
										<label class="form-label" for="basic-default-fullname">Country Code</label>
										
										<select class="form-control" name="cc" required>
											<option  value="61">Aus (+61)</option>
										</select>
									</div>
									<div class="mb-3">
										<label class="form-label" for="basic-default-fullname">Phone</label>
										<input type="text" class="form-control" name="ph" value="<?php echo ($_POST['ph'] != "") ?$_POST['ph'] : "";  ?>"  placeholder="Phone" required />
									</div>
									<button type="submit" class="btn btn-primary">Send</button>
								</form>
							</div>
						</div>
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