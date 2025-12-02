<?php
include "include/common.php";

$date="";
$category="";
$arrCategories=getCategories();
if(isset($_GET['cat']) && !empty($_GET['cat']))
{
	$tempCat=trim($_GET['cat']);
	if(isset($arrCategories[$tempCat]))
	{
		$category=$tempCat;
	}
}
if(isset($_GET['date']) && !empty($_GET['date']))
{
	$tempDate=$_GET['date'];
	$tsDate=strtotime($tempDate);
	if($tsDate!="")
	{
		$date=date("Y-m-d",$tsDate);
	}
}
if($date=="")
{
	echo "Invalid date.";
	die();
}
if($category=="")
{
	echo "Invalid category.";
	die();
}

$arrEventData=[
	"rec_id"=>
	"event_date"
];

$recId="";
$objDatewiseEvents=new ZOHO_DATEWISE_EVENTS();
$objDatewiseEvents2=new ZOHO_DATEWISE_EVENTS_2();
$objSeasonDates=new ZOHO_SEASON_DATES();
$rsltResponse=$objDatewiseEvents2->getEventsByDateAndCategory($date,$category);
$rowResponse=[];
if($rowResponse=$rsltResponse->fetch_assoc())
{
	$recId=$rowResponse['rec_id'];
}

// Process after submit
if(isset($_POST['submit']))
{
	$season="";
	$year="";
	$dayOfseason=0;
	$weekOfSeason=0;
	$seasonStartDate="";

	// Get season year from event date
	$rsltSeasonStartDate=$objSeasonDates->getCurrentSeasonBookingStartDate();
	if($rsltSeasonStartDate)
	{
		if($rowSeasonStartDate=$rsltSeasonStartDate->fetch_assoc())
		{
			$year=$rowSeasonStartDate['year'];
			$season=$rowSeasonStartDate['season'];
			$seasonStartDate=$rowSeasonStartDate['booking_start_date'];
			// Get day of season and week of season from event date
			$dayOfseason=calculateDays($seasonStartDate,$_POST['date']);
			$weekOfSeason=calculateWeek($seasonStartDate,$_POST['date']);
		}
	}
	else
	{
		
	}
	// Events 1 table
	$arrEvent=[];
	$arrEvent['event_date']=$_POST['date'];
	$arrEvent[$_POST['cat']]=$_POST['event_description'];
	$arrEvent['day_of_season']=$dayOfseason;
	$arrEvent['week_of_season']=$weekOfSeason;
	$arrEvent['season']=$season;
	$arrEvent['year']=$year;


	$mainRecId="";
	$rsltResponse=$objDatewiseEvents->getEventsByDate($date);
	$rowResponse=[];
	if($rowResponse=$rsltResponse->fetch_assoc())
	{
		$mainRecId=$rowResponse['rec_id'];
	}
	if($mainRecId!="")
	{
		$arrEventUpdate=[];
		$arrEventUpdate[$_POST['cat']]=$_POST['event_description'];
		$rslt=$objDatewiseEvents->updateEvent($mainRecId,$arrEventUpdate);
		if($rslt)
		{
			
		}
	}
	else
	{
		$rslt=$objDatewiseEvents->insertEvent($arrEvent);
		if($rslt)
		{

		}
	}


	// Events 2 table
	$arrEvent=[];
	$arrEvent['event_date']=$_POST['date'];
	$arrEvent['event_category']=$_POST['cat'];
	$arrEvent['event_description']=$_POST['event_description'];
	$arrEvent['day_of_season']=$dayOfseason;
	$arrEvent['week_of_season']=$weekOfSeason;
	$arrEvent['season']=$season;
	$arrEvent['year']=$year;

	$msg="";
	if($recId!="")
	{
		$arrEventUpdate=[];
		$arrEventUpdate['event_description']=$_POST['event_description'];
		$rslt=$objDatewiseEvents2->updateEvent($recId,$arrEventUpdate);
		if($rslt)
		{
			$msg="upd";
		}
		else
		{
			$msg="updF";
		}
	}
	else
	{
		$rslt=$objDatewiseEvents2->insertEvent($arrEvent);
		if($rslt)
		{
			$msg="ins";
		}
		else
		{
			$msg="insF";
		}
	}
	header("Location:datewise_events.php?msg=".$msg);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	    <?php
			// Include common header for all pages 
			include "include/common_head.php";
		?>
	</head>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
			// Include common menu for all pages 
			include "include/common_main_menu.php";
		?>
		<div>
			<div class="right_col" role="main" style="min-height: 3573px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Add/Update Event</h3>
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
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <br>
                    <form id="demo-form2" class="form-horizontal form-label-left" method="POST">
                      <?php 
							$id="date";
					  ?>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Date : 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12 control-label" style="text-align: left;">
                        	<b><?php echo format_date(getValue($id));?></b>
                        	<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo $date;?>" readonly>
                        </div>
                      </div>
                      	<?php 
							$id="cat";
						?>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Category : 
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12 control-label" style="text-align: left;">
                        	<b><?php echo $arrCategories[$category];?></b>
                         	<input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo getValue($id);?>" readonly>
                        </div>
                      </div>
                      <?php 
							$id='event_description';
						?>
                      <div class="form-group">
                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Description : </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <textarea  name="<?php echo $id; ?>" id="<?php echo $id; ?>" rows="5" class="form-control"><?php echo getValue($id);?></textarea>
                        </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        	<a href="datewise_events.php">
                          <button class="btn btn-primary" type="button">Cancel</button></a>
                          <button type="submit" class="btn btn-success" name="submit">Submit</button>
                        </div>
                      </div>

                    </form>
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
<?php
function getValue($id)
{
	global $rowResponse;
	$value="";
	if(isset($_POST[$id]))
	{
		$value=$_POST[$id];
	}
	else if(isset($rowResponse[$id]))
	{
		$value=$rowResponse[$id];
	}
	else if(isset($_GET[$id]))
	{
		$value=$_GET[$id];
	}
	return $value;
}
?>