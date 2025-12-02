<?php 
include __DIR__."/curl_zoho/class/zoho_methods.class.php";
// Database connection
$host="localhost";
$username="root";
$password="iRg7QOwKmTdO10mB";
$db="asc";
$conn=new mysqli($host,$username,$password,$db);

// Get Parameters
$to="";
$from="";
$message="";
$reference="";
$blError=false;

if(isset($_REQUEST['to']))
{
	$to = $_REQUEST["to"]; //The receiving mobile number
}
else
{
	$blError=true;
}
if(isset($_REQUEST['from']))
{
	$from =$_REQUEST["from"]; //The sending mobile number 
}
else
{
	$blError=true;
}
if(isset($_REQUEST["message"]))
{
	$message = urldecode($_REQUEST["message"]); //SMS content 
}
if(isset($_REQUEST["ref"]))
{
	$reference=$_REQUEST["ref"];
}

$arrFormattedNumber=checkMobileNumber($from);
$mobileFormatted=$arrFormattedNumber['mobile'];

if($blError)
{
	echo "No data supplied.";
	die();
}
else
{
	// Check if it is an otp out message
	$optout=false;

	if($message!="")
	{
		$checkMessage=strtolower($message);
		if(strpos($checkMessage,"stop")===false && strpos($checkMessage,"optout")===false && strpos($checkMessage,"unsubscribe")===false && strpos($checkMessage,"opt out")===false && strpos($checkMessage,"opt-out")===false)
		{
			$optout=false;
		}
		else
		{
			$optout=true;	
		}
	}
	$contact_id="";
	$contact_type="";
	$referenceFound=true;
	$objZoho=new ZOHO_METHODS();
	if($objZoho->checkTokens())
	{
		// If reference number is passed from sms broadcast
		if($reference!="")
		{
			/*
			echo $qrySel="SELECT * FROM `sms_log` WHERE id='$reference'";
			$rsltOp=mysqli_query($conn,$qrySel);
			if($rsltOp->num_rows!=0)
			{
				// Reference number found in database than get contact id from that
				echo "Ref found in database.<br>";
				$row=$rsltOp->fetch_assoc();
				$contact_id=$row['contact_id'];
				$contact_type=$row['contact_type'];	
				if($contact_id!="")
				{
					$arrContact=[];
					$module="";
					// Check type of contact
					if($contact_type=="contacts")
					{
						$module="All_Contacts";
						$arrContact['Sms_opt_out']=true;
					}
					else if($contact_type=="prospect")
					{
						$module="Prospects";
						$arrContact['Mobile_Opt_Out']=true;
					}
					else
					{
						$module="Parent_Guardian";
						$arrContact['SMS_Opt_Out']=true;
						
					}
					
					$arrUpdateContact=[];
					$arrUpdateContact[]=$arrContact;

					$respUpdateContact=$objZoho->updateRecord($module,$contact_id,$arrUpdateContact);

					if($respUpdateContact['data'][0]['code']=="SUCCESS")
					{
						echo "Updated successfully";
					}
					else
					{
						echo "Failed to update";
						echo "<pre>";
						print_r($respUpdateContact);
					}
				}	
			}
			else
			{
				echo "Ref id not found in database.<br>";
			}
			*/
		}
		else
		{
			echo "Ref id not passed from sms broadcast.<br>";
		}
		// if reference id is not found in db or not passes by sms broadcast then search using mobile number
		if($contact_id=="")
		{
			if($optout)
			{
				// Search contact with mobile number
				echo "Search with mobile number : ".$mobileFormatted."<br>";
				// ********************** Search in all contacts module ***************************
				$criteria="(Primary_Phone:equals:".$mobileFormatted.")";
				$arrParams['criteria']=$criteria;
				$respSearchContacts=$objZoho->searchRecords("All_Contacts",$arrParams);
				
				if(count($respSearchContacts['data'])>0)
				{
					echo "Contacts  Found : <br>";
					print_r($respSearchContacts);
					foreach ($respSearchContacts['data'] as $key => $arrSingleContact) 
					{
						$arrContact=[];
						$arrContact['Sms_opt_out']=true;
						$allContactsId=$arrSingleContact['id'];
						$contact_id=$allContactsId;
						$contact_type="contacts";

						$arrUpdateContact=[];
						$arrUpdateContact[]=$arrContact;

						$respUpdateContact=$objZoho->updateRecord("All_Contacts",$allContactsId,$arrUpdateContact);
						if($respUpdateContact['data'][0]['code']=="SUCCESS")
						{
							echo "Updated successfully : ".$allContactsId."<br>";
						}
						else
						{
							echo "Failed to update";
							echo "<pre>";
							print_r($respUpdateContact);
						}	
					}
				}
				else
				{
					echo "Not found in contacts.<br>";
				}
				// ********************************************************************
				// **************** Search in prospects module *****************
				$criteria="(Primary_Phone:equals:".$mobileFormatted.")";
				$arrParams['criteria']=$criteria;
				$respSearchContacts=$objZoho->searchRecords("Prospects",$arrParams);
				
				if(count($respSearchContacts['data'])>0)
				{
					echo "Contacts  Found : <br>";
					print_r($respSearchContacts);
					foreach ($respSearchContacts['data'] as $key => $arrSingleContact) 
					{
						$arrContact=[];
						$arrContact['Mobile_Opt_Out']=true;
						$allContactsId=$arrSingleContact['id'];
						$contact_id=$allContactsId;
						$contact_type="prospect";

						$arrUpdateContact=[];
						$arrUpdateContact[]=$arrContact;

						$respUpdateContact=$objZoho->updateRecord("Prospects",$allContactsId,$arrUpdateContact);
						if($respUpdateContact['data'][0]['code']=="SUCCESS")
						{
							echo "Updated successfully : ".$allContactsId."<br>";
						}
						else
						{
							echo "Failed to update";
							echo "<pre>";
							print_r($respUpdateContact);
						}	
					}
				}
				else
				{
					echo "Not found in prospects.<br>";
				}
				// ****************** Search in parent module ********************
				$criteria="(Primary_Phone:equals:".$mobileFormatted.")";
				$arrParams['criteria']=$criteria;
				$respSearchParent=$objZoho->searchRecords("Parent_Guardian",$arrParams);
				
				if(count($respSearchParent['data'])>0)
				{
					echo "Parents  Found : <br>";
					print_r($respSearchParent);
					foreach ($respSearchParent['data'] as $key => $arrSingleParent) 
					{
						$arrParent=[];
						$arrParent['SMS_Opt_Out']=true;
						$parentId=$arrSingleParent['id'];
						$contact_id=$parentId;
						$contact_type="parent";

						$arrUpdateParent=[];
						$arrUpdateParent[]=$arrParent;

						$respUpdateParent=$objZoho->updateRecord("Parent_Guardian",$parentId,$arrUpdateParent);
						if($respUpdateParent['data'][0]['code']=="SUCCESS")
						{
							echo "Updated successfully : ".$parentId."<br>";
						}
						else
						{
							echo "Failed to update";
							echo "<pre>";
							print_r($respUpdateParent);
						}
					}
				}
			}
		}
	
		// Insert sms record
		$arrSMSRecord=[
			'Message_Content'=>$message,
			'From_Number'=>(string)$mobileFormatted,
			'Type'=>'Received'
		];

		if($contact_type=="contacts")
		{
			$arrSMSRecord['Contact_Name']=$contact_id;
		}
		else if($contact_type=="prospect")
		{
			$arrSMSRecord['Prospect_Name']=$contact_id;
		}
		else
		{
			$arrSMSRecord['Parent_Name']=$contact_id;
		}

		$arrInsertSMS=[];
		$arrInsertSMS[]=$arrSMSRecord;
		$respInsertSMS=$objZoho->insertRecord("SMS_Records",$arrInsertSMS);


		if($respInsertSMS['data'][0]['code']=="SUCCESS")
		{
			echo "Inserted successfully";
		}
		else
		{
			echo "Failed to insert sms";
			echo "<pre>";
			print_r($respInsertSMS);
		}
	}

	$currentTime=date("Y-m-d H:i:s");
	$details="To = ".$to."\n From = ".$from."\n Message = ".$message."\n reference = ".$reference;
	$qryIns="INSERT INTO `inbound_msg` (`to_number`, `from_number`,`from_number_formatted`, `message`, `ref`, `CurrentTime`) VALUES ('$to','$from','$mobileFormatted','$message','$reference','$currentTime');";
	$rsltOp=mysqli_query($conn,$qryIns);
	// Lets send an email with the message data 

	$email_message = "Inbound SMS sent to $to.\nSent From: $from\nMessage: $message"; 
	//mail("jaykishan@qltech.com.au", "Inbound SMS", $details, "From: email@example.com"); 
}

function checkMobileNumber($mobile)
{
	$flag=true;
	$mobile=trim($mobile);
	$mobile=preg_replace('/[^0-9]/','', $mobile);
	$length=strlen($mobile);
	
	if($length==10)
	{
	}
	else if($length==9)
	{
		$mobile="0".$mobile;
	}
	else if($length>10)
	{
		$mobile=preg_replace ( "/^61/","",$mobile);
		if(strlen($mobile)==10)
		{

		}
		else if(strlen($mobile)==9)
		{
			$mobile="0".$mobile;
		}
		else 
		{
			$flag=false;
		}
	}
	else if($length<9)
	{
		$flag=false;
	}

	if($flag)
	{
		$firstPart=substr($mobile,0,4);
		$secondPart=substr($mobile,4,3);
		$thirdPart=substr($mobile,7,3);
		$mobile=$firstPart." ".$secondPart." ".$thirdPart;
		$secondDigit=substr($mobile,1,1);
		if($secondDigit!=4)
		{
			$flag=false;
		}

	}
	else
	{
		$flag=false;
	}

	$arrMobile=[];
	$arrMobile['status']=$flag;
	$arrMobile['mobile']=$mobile;
	return $arrMobile;
}
?>