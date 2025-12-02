<?php
include "include/common.php";
$objDatewiseEvents2=new ZOHO_DATEWISE_EVENTS_2();

$season="Winter";
$year="2022";

if(isset($_GET['season']) && isset($_GET['year'])){
	$season=$_GET['season'];
	$year=$_GET['year'];	
}
$rsltResponse=$objDatewiseEvents2->getAllDatesBySeasonYear($season,$year);
$arrCategories=getCategories();
$rsltUniqueContacts=$rsltResponse;

// Check action
$action="";
if(isset($_GET['action']) && !empty($_GET['action']))
{
	$action=$_GET['action'];
	switch($action)
	{
		case 'del':
			$msg="delF";
			$id="";
			if(isset($_GET['id']) && !empty($_GET['id']))
			{
				$id=trim($_GET['id']);
			}

			if($id!="")
			{
				$rsltDelete=$objDatewiseEvents2->getEventsById($id);
				if($rowDelete=$rsltDelete->fetch_assoc())
				{
					$date=$rowDelete['event_date'];
					$cat=$rowDelete['event_category'];

					// Get main event 1 Id 
					$objDatewiseEvents=new ZOHO_DATEWISE_EVENTS();
					$mainRecId="";
					$rsltResponse=$objDatewiseEvents->getEventsByDate($date);
					$rowResponse=[];
					if($rowResponse=$rsltResponse->fetch_assoc())
					{
						$mainRecId=$rowResponse['rec_id'];
					}

					// Delete event 2
					$recId=$rowDelete['rec_id'];
					if($rsltDelete=$objDatewiseEvents2->deleteRecord($recId))
					{
						// Update event 1
						$arrEvent=[];
						$arrEvent[$cat]="";

						$rsltUpdate=$objDatewiseEvents->updateEvent($mainRecId,$arrEvent);
						$msg="del";
					}
				}
			}
			header("Location:datewise_events.php?msg=".$msg);
		break;
	}
}
$msg="";
$msgType="";
if(isset($_GET['msg']) && !empty($_GET['msg']))
{
	$msg=trim($_GET['msg']);
	switch ($msg) 
	{
		case 'ins':
			$msg="Event inserted successfully.";
			$msgType="s";
		break;
		case 'upd':
			$msg="Event updated successfully.";
			$msgType="s";
		break;
		case 'insF':
			$msg="Event failed to insert.";
			$msgType="f";
		break;
		case 'updF':
			$msg="Event failed to update.";
			$msgType="f";
		break;
		case 'del':
			$msg="Event deleted successfully.";
			$msgType="s";
		break;
		case 'delF':
			$msg="Event failed to delete.";
			$msgType="f";
		break;
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
                <h3>Events</h3>
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
            	if($msgType=="s")
            	{
            ?>
	            <div class="alert alert-success alert-dismissible fade in" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			        </button>
			        <strong>Success!</strong> <?php echo $msg;?>
			    </div>
			<?php
				}
				elseif($msgType=="f")
				{
			?>
				<div class="alert alert-danger alert-dismissible fade in" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			        </button>
			        <strong>Failure!</strong> <?php echo $msg;?>
			    </div>
			<?php
				}
			?>
            <div class="row">
            	<form name="frmEvent" method="get" action="manage_datewise_events.php">
	            	<div class="col-sm-4">
	                    <div class="form-group">
	                        <div class="input-group date" id="myDatepicker2">
	                            <input type="text" class="form-control" id="eventDatePicker" placeholder="Event Date" name="date" required>
	                            <span class="input-group-addon">
	                               <span class="glyphicon glyphicon-calendar"></span>
	                            </span>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-sm-4">
	                	<div class="form-group">
			                <select class="form-control" name="cat" required>
									<option value="">--- Select Category ---</option>
								<?php
									foreach ($arrCategories as $key => $value) 
									{
								?>
										<option value="<?php echo $key;?>"><?php echo $value;?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>
					 <div class="col-sm-4">
					 	<div class="form-group">
					 		<input type="submit" class="btn btn-success" value="Add/Update" />
					 	</div>
					 </div>
	            </form>
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Datewise Events - <?php echo $season." ".$year;?><small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>

                  <div class="x_content">
                    <div class="table-responsive">
                      <table class="table table-striped jambo_table bulk_action">
                        <thead>
                          <tr class="headings">
                            <th>
                              #
                            </th>
                            <th class="column-title">Date</th>
                            <th class="column-title">Day</th>
                            <th class="column-title">Category</th>
                            <th class="column-title" style="width:25%">Description</th>
                            <th class="column-title">Created By</th>
                            <th class="column-title">Modified By</th>
                            <th class="column-title no-link last"><span class="nobr">Action</span>
                            </th>
                            <th class="bulk-actions" colspan="7">
                              <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
                        <?php
							$i=1;
							while ($rowUniqueContacts=$rsltUniqueContacts->fetch_assoc()) 
							{
								
							?>
	                          <tr class="even pointer">
	                            <td class="a-center ">
	                              <?php echo $i; ?>
	                            </td>
	                            <td class=" "><?php echo format_date($rowUniqueContacts['event_date']);?></td>
	                            <td class=" "><?php echo $rowUniqueContacts['day_of_season'];?></td>
	                            <td class=" "><?php echo $arrCategories[$rowUniqueContacts['event_category']];?></td>
	                            <td class=" "><?php echo $rowUniqueContacts['event_description'];?></td>
	                            <td class=" "><?php echo $rowUniqueContacts['created_by_name']."<br>".format_date_time($rowUniqueContacts['created_at']); ?></td>
	                            <td class=" "><?php echo $rowUniqueContacts['modified_by_name']."<br>".format_date_time($rowUniqueContacts['modified_at']); ?></td>
	                            <td class=" last"><a href="manage_datewise_events.php?date=<?php echo $rowUniqueContacts['event_date']?>&cat=<?php echo $rowUniqueContacts['event_category'];?>">Update</a>
	                            	 | <a href="javascript:confirmBox('<?php echo $rowUniqueContacts['rec_id'];?>')">Delete</a>
	                            </td>
	                          </tr>
	                        <?php
								$i++;
							}
						?>
                        </tbody>
                      </table>
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
		<script>
		    $("#eventDatePicker").datetimepicker(
		    	{
		    		format: 'DD-MM-YYYY'
		    	}
		    );

		    function confirmBox(id)
		    {
		    	if(confirm("Are you sure you want to delete this event?"))
		    	{
		    		url="datewise_events.php?action=del&id="+id;
		    		window.location=url;
		    	}
		    	else
		    	{
		    		return false;
		    	}
		    }
		</script>
	</body>
</html>