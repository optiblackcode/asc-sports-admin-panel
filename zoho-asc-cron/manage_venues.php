<?php
require_once "class/venues.class.php";
$objVenue=new VENUES();
$action="";
if(isset($_REQUEST['action']))
{
	$action=$_REQUEST['action'];
}

switch($action)
{
	// Case to insert or update venues 
	case 'manage':
		$zoho_id="";
		$venue_name="";
		if(isset($_REQUEST['zoho_id']))
		{
			$zoho_id=$_REQUEST['zoho_id'];
		}

		if(isset($_REQUEST['venue_name']))
		{
			$venue_name=$_REQUEST['venue_name'];
		}

		if($zoho_id!="" && $venue_name!="")
		{
			// Venue array
			$arrVenue=[];
			$arrVenue['zoho_id']=addslashes($zoho_id);
			$arrVenue['venue_name']=addslashes($venue_name);

			$rsltInsert=$objVenue->insertOrUpdate($arrVenue);
			if($rsltInsert)
			{
				echo "Success";
			}
			else
			{
				echo "Fail";
			}
		}
		else
		{
			echo "Blank parameteres.";
		}
	break;
	// Case to get all the venues
	case 'get';
		$arrVenues=[];
		$rsltSel=$objVenue->getAll();
		if($rsltSel)
		{
			while($row=$rsltSel->fetch_assoc())
			{
				$arrVenues[]=$row['venue_name'];
			}
		}
		$jsonVenues=json_encode($arrVenues);
		echo $jsonVenues;
	break;
	case 'delete':
		$zoho_id="";
		if(isset($_REQUEST['zoho_id']))
		{
			$zoho_id=$_REQUEST['zoho_id'];
		}
		if($zoho_id!="")
		{
			$zoho_id=addslashes($zoho_id);
			$rsltDel=$objVenue->delete($zoho_id);
			if($rsltDel)
			{
				echo "Success";
			}
			else
			{
				echo "Failed to delete";
			}
		}
	break;
	default:
		echo "No action set.";
	break;
}
?>