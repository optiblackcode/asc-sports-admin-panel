<?php
include __DIR__."/curl_zoho/class/zoho_methods.class.php";
//sleep(5);
// Get email trigger date
$triggerDate=emailTriggerDate();

if(isset($_POST['camp_id']))
{
	$campId=trim($_POST['camp_id']);
	notifymelog($campId);
	$objZoho=new ZOHO_METHODS();
	if($objZoho->checkTokens())
	{
		// Start getting camp information
		$arrCamps=$objZoho->getRecordById("Camps",$campId);
		if(!empty($arrCamps))
		{
			$arrCamp=$arrCamps['data'][0];
			$sports=$arrCamp['Sports']['name'];
			$state=$arrCamp['State'];

			// Find notify me contacts with matching state or sports reqirement
			echo $sports=escapeBracket($sports);
			echo $state=escapeBracket($state);

			if($sports!="" && $state!="")
			{
				$criteria="(((Notify_Me_Sports:equals:".$sports.") and (Notify_Me_State:equals:".$state.") and (Notify_Email_Sent_Pick_List:equals:No)) or ((Notify_Me_Sports:equals:NS) and (Notify_Me_State:equals:".$state.") and (Notify_Email_Sent_Pick_List:equals:No)) or ((Notify_Me_Sports:equals:".$sports.") and (Notify_Me_State:equals:NS) and (Notify_Email_Sent_Pick_List:equals:No)))";
			
				notifymelog($criteria);
				$arrParams=[];
				$arrParams['criteria']=$criteria;
				//$arrParams['cvid']="3108913000021028133";
				$arrParams['per_page']=200;
				// ******************* Search from prospect interactions ********************
				$moreRecord=true;
				$maxCount=40;
				$i=1;
				while($i<=$maxCount && $moreRecord)
				{
					
					$arrParams['page']=$i;
					$respSearchContacts=$objZoho->searchRecords("Prospect_Interactions",$arrParams);
					if(isset($respSearchContacts['data']))
					{
						if(count($respSearchContacts['data'])>0)
						{
							foreach ($respSearchContacts['data'] as $key => $arrRecord) 
							{
								$arrProspectInteraction=[];
								$arrProspectInteraction['Notify_Me_Email_Trigger']=true;
								$arrProspectInteraction['Notify_Me_Email_Trigger_Date']=$triggerDate;

								$arrUpdateProspectInteraction=[];
								$arrUpdateProspectInteraction[]=$arrProspectInteraction;

								$arrTrigger=["workflow"];
								$rsltUpdate=$objZoho->updateRecord("Prospect_Interactions",$arrRecord['id'],$arrUpdateProspectInteraction,$arrTrigger);
							}
							// ******************************
							// Check if more records exirst
							if(isset($respSearchContacts['info']))
							{
								if($respSearchContacts['info']['more_records']!=1)
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
			}else{
				$notifyme = "Camp ID : ".$campId."\n";
				$notifyme .= "sports : ".$sports."\n";
				$notifyme .= "state : ".$state."\n";
				notifymelog($notifyme);
			}

		}
		else
		{
			echo "Not found";
		}
	}
}

// Escape brackets for zoho api call criteria
function escapeBracket($str)
{
	$str=str_replace("(", "\(", $str);
	$str=str_replace(")", "\)", $str);
	return $str;
}

function emailTriggerDate()
{
	$emailHour=18;

	$date = new DateTime("now", new DateTimeZone('Australia/Melbourne') );
	//$hour=$date->format('H');
	$offset=$date->getOffset();
	$offset=ceil($offset/60/60);
	
	$triggerDate="";
	$triggerDate=$date->format('Y-m-d\TH:i:s+'.$offset.':00');
	// if($hour<=$emailHour){
	// 	$triggerDate=$date->format('Y-m-d\T'.$emailHour.':00:00+'.$offset.':00');
	// }
	// else{
	// 	$triggerDate=$date->format('Y-m-d H:i:s');
	// 	$triggerDate=date("Y-m-d\T".$emailHour.":00:00+".$offset.":00", strtotime($triggerDate." +1 Day"));
	// }
	return $triggerDate;
}
function notifymelog($notifyme){
	$file="notifyme.log";
	// Write to error.log file
	$myfile = fopen($file, "a");
	fwrite($myfile, $notifyme);
	fclose($myfile);
}
?>