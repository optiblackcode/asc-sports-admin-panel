<?php
include "include/common.php";

$imagesDirAbsPath=__DIR__."/images/talents";
$imagesDirUrl="images/talents";

$objTalents=new BROCHURE_TALENTS();

$limit=10;
$currentPage=1;
$totalRecords=0;
$talentName="";
$talentSports="";

$arrParams=[];
$arrParams['limit']=$limit;
if(isset($_GET['page']) &&  !empty($_GET['page']))
{
	$currentPage=$_GET['page'];
}
$arrParams['page']=$currentPage;
if(isset($_GET['name']) &&  !empty($_GET['name']))
{
	$talentName=trim($_GET['name']);
}
$arrParams['name']=$talentName;
if(isset($_GET['sports']) &&  !empty($_GET['sports']))
{
	$talentSports=$_GET['sports'];
}
$arrParams['sports']=$talentSports;

$arrResult=$objTalents->searchTalents($arrParams);

$rsltAllTalents=$arrResult['rslt'];
$totalRecords=$arrResult['num_rows'];

// Calculate total pages
$totalPages=ceil($totalRecords/$limit);

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

        $rsltTalent=$objTalents->getTalentById($id);
        if($rsltTalent)
        {
          if($rsltTalent->num_rows==1)
          {
            // Delete image
            $rowTalent=$rsltTalent->fetch_assoc();
            $imageToDelete=$rowTalent['talent_image'];
            if(file_exists($imagesDirAbsPath."/".$imageToDelete))
            {
              unlink($imagesDirAbsPath."/".$imageToDelete);
            }

            // Delete record
            if($rsltDelete=$objTalents->deleteRecord($id))
            {
              $msg="del";
            }
          }
        }
      }
      header("Location:talents.php?msg=".$msg);
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
      $msg="Talent inserted successfully.";
      $msgType="s";
    break;
    case 'upd':
      $msg="Talent updated successfully.";
      $msgType="s";
    break;
    case 'insF':
      $msg="Talent failed to insert.";
      $msgType="f";
    break;
    case 'updF':
      $msg="Talent failed to update.";
      $msgType="f";
    break;
    case 'del':
      $msg="Talent deleted successfully.";
      $msgType="s";
    break;
    case 'delF':
      $msg="Talent failed to delete.";
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
                <h3>Talents</h3>
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
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <form name="frmSearch" method="get" action="manage_datewise_events.php" _lpchecked="1" style="margin:10px 0px;">
                  		<div class="row">
			            	<div class="col-sm-4">
			                    <div class="form-group">
			                        <input type="text" class="form-control" id="talentName" placeholder="Talent Name" name="talentName" value="<?php echo $talentName;?>">
			                    </div>
			                </div>
			                <div class="col-sm-4">
			                	<?php
			                		$arrSports=getSports();
			                	?>
			                	<div class="form-group">
					                <select class="form-control" name="lstSports" id="lstSports">
										<option value="">--- Select Sports ---</option>
										<?php
											foreach ($arrSports as $key => $value) {
												$selected="";
												if($talentSports==$key)
												{
													$selected="selected";
												}
										?>
												<option value="<?php echo $key;?>" <?php echo $selected;?>><?php echo $value;?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>
							 <div class="col-sm-4">
							 	<div class="form-group">
							 		<input type="button" class="btn btn-success" value="Search" onclick="fnSearch();">
							 		<input type="button" class="btn btn-primary" value="Clear" onclick="fnClear();">
							 		<div style="float:right;">
				                      <a href="manage_talent.php" class="btn btn-success">Add New Talent</a>
				                    </div>
							 	</div>
							 </div>
						</div>
	            	</form>
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
                            <th class="column-title">Image</th>
                            <th class="column-title">Name</th>
                            <th class="column-title">Sports</th>
                            <th class="column-title">Created At</th>
                            <th class="column-title">Modified At</th>
                            <th class="column-title no-link last"><span class="nobr">Action</span>
                            </th>
                          </tr>
                        </thead>

                        <tbody>
                        <?php
                          $i=(($currentPage-1)*$limit)+1;
                          while ($rowTalent=$rsltAllTalents->fetch_assoc()) 
                          {
                            
                        ?>
                            <tr class="even pointer">
                              <td class="a-center ">
                                <?php echo $i; ?>
                              </td>
                              <td class=" ">
                                <img src="<?php echo $imagesDirUrl."/".$rowTalent['talent_image'];?>" class="talent-image" />
                              </td>
                              <td class=" "><?php echo $rowTalent['talent_name'];?></td>
                              <td class=" "><?php echo $rowTalent['talent_sports'];?></td>
                              <td class=" "><?php echo $rowTalent['created_at'];?></td>
                              <td class=" "><?php echo $rowTalent['modified_at'];?></td>
                              <td class=" last"><a href="manage_talent.php?id=<?php echo $rowTalent['rec_id']?>">Update</a>
                                 | <a href="javascript:confirmBox('<?php echo $rowTalent['rec_id'];?>')">Delete</a>
                              </td>
                            </tr>
                          <?php
                              $i++;
                            }
                          ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="dataTables_paginate paging_simple_numbers" id="datatable-responsive_paginate">
                    	<ul class="pagination">
                    		<li class="paginate_button previous disabled" id="datatable-responsive_previous">
                    			<a href="#" aria-controls="datatable-responsive" data-dt-idx="0" tabindex="0">Previous</a>
                    		</li>
                    		<?php
                    			for($i=1;$i<=$totalPages;$i++)
                    			{
                    				$active="";
                    				if($i==$currentPage)
                    				{
                    					$active="active";
                    				}
                    		?>
		                    		<li class="paginate_button <?php echo $active;?>">
		                    			<a href="javascript:pageRedirect(<?php echo $i;?>)" aria-controls="datatable-responsive" data-dt-idx="<?php echo $i;?>" tabindex="0"><?php echo $i;?></a>
		                    		</li>
		                    <?php
		                    	}
		                    ?>
                    		<li class="paginate_button next" id="datatable-responsive_next">
                    			<a href="#" aria-controls="datatable-responsive" data-dt-idx="7" tabindex="0">Next</a>
                    		</li>
                    	</ul>
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
          if(confirm("Are you sure you want to delete this talent?"))
          {
            url="talents.php?action=del&id="+id;
            window.location=url;
          }
          else
          {
            return false;
          }
        }

        // Pagination and search
        var page=<?php echo $currentPage;?>;
        var talentName='<?php echo $talentName;?>';
        var talentSports='<?php echo $talentSports;?>';

        function pageRedirect(tempPage=1)
        {
        	page=tempPage;
        	redirect();
        }
        function fnSearch()
        {
        	var sports=$("#lstSports").val();
        	var name=$("#talentName").val();

        	talentName=name;
        	talentSports=sports;
        	page=1;
        	redirect();
        }

        function fnClear()
        {
        	talentName='';
        	talentSports='';
        	page=1;
        	redirect();
        }
        function redirect()
        {
        	var url="talents.php?";
        	if(talentName!="")
        	{
        		url=url+"&name="+talentName;
        	}
        	if(talentSports!="")
        	{
        		url=url+"&sports="+talentSports;
        	}
        	if(page!="")
        	{
        		url=url+"&page="+page;
        	}

        	window.location.href=url;
        }
    </script>
  </body>
</html>