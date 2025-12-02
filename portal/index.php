<?php
	$isLoginPage=true;
	include "include/common.php";

	$arrLogin=[
		'username'=>'',
		'password'=>''
	];

	if(isset($_POST['sbLogin']))
	{
		$arrLogin['username']=trim($_POST['txtUsername']);
		$arrLogin['password']=trim($_POST['pwPassword']);

		$arrUser=$objUser->checkCredentials($arrLogin);
		
		
		
		
		
		if(is_array($arrUser))
		{
			if($arrUser['is_blocked']!=1)
			{
				$arrLogin['password']=md5($arrLogin['password']);
				if($arrLogin['password']==$arrUser['password'])
				{
					$arrUserUpdate=[];
					$arrUserUpdate['retry_attempt']='0';
					$arrUserUpdate['last_login']=date("Y-m-d H:i:s");
					$objUser->updateUser($arrUser['user_id'],$arrUserUpdate);

					$_SESSION['user_id']=$arrUser['user_id'];
					$_SESSION['username']=$arrUser['username'];
					$_SESSION['name']=$arrUser['name'];

					// Insert login_activity records
					$objLoginActivity=new LOGIN_ACTIVITY();
					$arrLoginActivity=[];
					$arrLoginActivity['user_id']=$arrUser['user_id'];
					$arrLoginActivity['ip_address']=$_SERVER['REMOTE_ADDR'];
					$arrLoginActivity['date_time']=date("Y-m-d H:i:s");
					$arrLoginActivity['user_agent']=$_SERVER['HTTP_USER_AGENT'];
					
					$rslt=$objLoginActivity->insertActivity($arrLoginActivity);
					header("Location:".$objUser->dashboardUrl);
					die();
				}
				else
				{
					$totalAttemps=5;
					$retryAttempt=$arrUser['retry_attempt'];
					$retryAttempt++;

					$arrLogin['error_msg']="Invalid username/password.";
					$arrUserUpdate=[];
					if($retryAttempt>=$totalAttemps)
					{
						$arrUserUpdate['is_blocked']='1';
						$arrLogin['error_msg'].="<br>Your account is blocked. Please contact administrator.";
					}
					$arrUserUpdate['retry_attempt']=$retryAttempt;
					$objUser->updateUser($arrUser['user_id'],$arrUserUpdate);
					
				}
			}
			else
			{
				$arrLogin['error_msg']="Your account is blocked. Please contact administrator.";
			}	
		}
		else
		{
			$arrLogin['error_msg']="Invalid username/password.";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    <!-- Bootstrap -->
    <link href="vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="vendors/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="build/css/custom.min.css" rel="stylesheet">
    <?php
		// Include common header for all pages 
		include "include/common_head.php";
	?>
	</head>
	<body class="login">
	    <div>
	      <a class="hiddenanchor" id="signup"></a>
	      <a class="hiddenanchor" id="signin"></a>

	      <div class="login_wrapper">
	        <div class="animate form login_form">
	          <section class="login_content">
	            <form method="POST">
	              <h1>ASC Login</h1>
	              <div>
	                <input type="text" name="txtUsername" class="form-control" placeholder="Username" required="" />
	              </div>
	              <div>
	                <input type="password" name="pwPassword" class="form-control" placeholder="Password" required="" />
	              </div>
	              <div style="padding: 0px 0px 20px 0px;text-align: left;">
	              	<span class="text-danger"><?php echo $arrLogin['error_msg'];?></span>
	              </div>
	              <div>
	              	<input class="btn btn-info btn-lg" type="reset" name="btnReset" value="Reset" />
					<input class="btn btn-success btn-lg submit" type="submit" name="sbLogin" value="LogIn" />
	                
	              </div>

	              <div class="clearfix"></div>
	            </form>
	          </section>
	        </div>
	      </div>
	    </div>
	    <?php 
			// Include common footer for all pages
			include "include/common_footer.php";
		?>
  	</body>
<!--
	<body>
		<div class="dvLoginForm">
			<form name="frmLogin" method="POST">
				<div>
					<label>Username :
						<input type="text" name="txtUsername" value="">
					</label>
				</div>
				<div>
				<div>
					<label>Password :
						<input type="password" name="pwPassword" value="">
					</label>
				</div>
				<div>
					<input type="reset" name="btnReset" value="Reset" />
					<input type="submit" name="sbLogin" value="LogIn" />
				</div>
			</form>
		</div>
		<?php 
			// Include common footer for all pages
			//include "include/common_footer.php";
		?>
	</body>
-->
</html>