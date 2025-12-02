<?php
	$username="RBUH9jPnna";
	$password="BYdxh5JIu!";
	$db="asc_datastudio_reportingnew";
	$con = mysqli_connect("localhost",$username,$password,$db);
	$rawData = file_get_contents("php://input");
	parse_str($rawData, $get_array);
	$date = date("Y-m-d H:i:s");
	$id = $get_array['id'];
	$ZID = "ZOHO_".$get_array['id'];
	$Name = $get_array['Name'];
	$Primary_Phone = $get_array['Primary_Phone'];
	$State = $get_array['State'];
	$Role = $get_array['Role'];
	$Email = $get_array['Email'];
	
	
	$s = mysqli_query($con,"SELECT * FROM `coaches` WHERE `Email` = '$Email'");
	$count = mysqli_num_rows($s);
	if($count == 0){
		$qrySeld="INSERT INTO `coaches`(`ZOHOID`,`ZID`, `name`,`Email`, `Role`, `Primary_Phone`, `State`, `Date`) VALUES ('$id','$ZID','$Name','$Email','$Role','$Primary_Phone','$State','$date')";
		$result = mysqli_query($con, $qrySeld);
		$get_array = json_encode($get_array);
		$qrySel = "INSERT INTO `Coaches_Webhook`(`Data`, `Date`) VALUES ('$rawData','$date')";
		$result = mysqli_query($con, $qrySel);
	}
	
	
	

?>