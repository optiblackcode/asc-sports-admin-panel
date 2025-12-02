<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time',300);

require_once __DIR__."/class/execution_logs.class.php";
$objExecutionLog=new EXECUTION_LOGS();
$execusitonLogId=$objExecutionLog->log_id;

require_once __DIR__."/../curl_zoho/class/zoho_methods.class.php";
require_once __DIR__."/class/zoho_suburbs.class.php";

$objZoho=new ZOHO_METHODS();
$objSuburb=new ZOHO_SUBURBS();
try
{
	if($objZoho->checkTokens())
	{
		echo "<h1>Suburbs</h1>";
		// ************ Get all prospects and insert or update in database ***********
		$prospectsCreated=0;
		$prospectsUpdated=0;
		$moreRecord=true;
		$maxCount=500;
		//$maxCount=1;
		$i=1;
		while($i<=$maxCount && $moreRecord)
		{
			// Get records from prospects with flag 3108913000018410071
			$arrParams=[];
			$arrParams['page']=$i;
			$arrParams['per_page']=200; 
			$resp=$objZoho->getRecords("Suburb",$arrParams);
			if(isset($resp['data']))
			{
				if(count($resp['data'])>0)
				{
					foreach ($resp['data'] as $key => $arrZohoSuburb) 
					{
						// Insert/Update booking in database
						$arrDBSuburb=[];
						$arrDBSuburb["suburb_id"]=$arrZohoSuburb["id"];
						$arrDBSuburb["suburb_state_name"]=$arrZohoSuburb["Name"];
						$arrDBSuburb["suburb_name"]=$arrZohoSuburb["Suburb"];
						$arrDBSuburb["postcode"]=$arrZohoSuburb["Postcode"];
						$arrDBSuburb["city"]=$arrZohoSuburb["City"];
						$arrDBSuburb["state"]=$arrZohoSuburb["State"];
						$arrDBSuburb["country"]=$arrZohoSuburb["Country"];
						$arrDBSuburb["category"]=$arrZohoSuburb["Category"];
						$arrDBSuburb["region"]=$arrZohoSuburb["Region"];
						$arrDBSuburb["raw_data"]=json_encode($arrZohoSuburb);
						$arrDBSuburb["flag_complete"]='0';

						$rsltSuburb=$objSuburb->getSuburbByZohoId($arrDBSuburb['suburb_id']);
						if($rsltSuburb)
						{
							if($rsltSuburb->num_rows>0)
							{
								/*
								$rowFoundSuburb=$rsltSuburb->fetch_assoc();
								$recId=$rowFoundSuburb['rec_id'];

								// Update booking
								$rsltUpdate=$objSuburb->updateSuburb($recId,$arrDBSuburb);
								if($rsltUpdate)
								{
									$prospectsUpdated++;
									echo "<br>Updated successfully";
								}
								else
								{
									echo "<br>Failed to update.";
								}
								*/

							}	
							else
							{
								// Insret booking
								$rsltUpdate=$objSuburb->insertSuburb($arrDBSuburb);
								if($rsltUpdate)
								{
									$prospectsCreated++;
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
	}
}
catch(Exception $e)
{
	echo "<br>Exception";
	print_r($e);
}