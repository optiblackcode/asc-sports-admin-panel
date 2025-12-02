<?php
include "include/common.php";
require_once('../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php' );

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
                <h3>Camp Staff List</h3>
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
	  <?php
					
						if(isset($_GET['success'])){
							echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			        </button>
			        <strong>Success!</strong> Your Camp Group successfully Deleted.
			    </div>';
						}
						
				if(isset($_GET['success2'])){
							echo '<div class="alert alert-success alert-dismissible fade in" role="alert">
			        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			        </button>
			        <strong>Success!</strong> Your Camp Group successfully Edited.
			    </div>';
						}		
					?>
	  <?php
    // Pagination setup
    $host = "localhost";
    $username = "RBUH9jPnna";
    $password = "BYdxh5JIu!";
    $db = "asc_datastudio_reportingnew";
    $con = mysqli_connect("localhost", $username, $password, $db);

    $records_per_page = 10; // Change this to set how many records per page
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($current_page < 1) {
        $current_page = 1;
    }
    $offset = ($current_page - 1) * $records_per_page;

    // Query for total records
    $total_query = "SELECT COUNT(*) as total_records FROM `camp_group`";
    $total_result = mysqli_query($con, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_records = $total_row['total_records'];

    // Query for paginated records
    $qrySel = "SELECT * FROM `camp_group` ORDER BY `ID` DESC LIMIT $records_per_page OFFSET $offset";
    $result = mysqli_query($con, $qrySel);

    // Calculate total pages
    $total_pages = ceil($total_records / $records_per_page);
?>

	  
	  
      <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
              <table class="table">
                  <thead class="thead-dark">
                      <tr>
                          <th scope="col">#</th>
                          <th scope="col">Date</th>
                          <th scope="col">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php
                      while ($data = mysqli_fetch_assoc($result)) {
                          $raw = json_decode($data['data'], true);
                          $camps = $raw['camps'];

                          $objZoho = new ZOHO_METHODS();
                          try {
                              if ($objZoho->checkTokens()) {
                                  $arrcamp = $objZoho->getRecordById("Camps", $camps);
                                  if (count($arrcamp['data'])) {
                                      $Camp = $arrcamp['data'][0];
                                      $Name = $Camp['Name'];
                                      $Camp_Dates = $Camp['Camp_Dates'];
                                  }
                              } else {
                                  echo "no token";
                              }
                          } catch (Exception $e) {
                              $ResponseData = null;
                          }
                      ?>
                      <tr>
                          <th scope="row"><?php echo $Name . " (" . $Camp_Dates . ")"; ?></th>
                          <td><?php echo $data['date']; ?></td>
                          <td><a href="view_camp_group.php?id=<?php echo $data['ID']; ?>" class="btn btn-success">View Details</a></td>
                          <td><a href="delete_camp_group.php?id=<?php echo $data['ID']; ?>" class="btn btn-danger">Delete</a></td>
                          <td><a href="edit_camp_group.php?id=<?php echo $data['ID']; ?>" class="btn btn-warning">Edit</a></td>
                      </tr>
                      <?php } ?>
                  </tbody>
              </table>
          </div>
      </div>

      <!-- Pagination Links -->
      <nav aria-label="Page navigation">
          <ul class="pagination">
              <?php if ($current_page > 1): ?>
              <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" aria-label="Previous">
                      <span aria-hidden="true">&laquo;</span>
                  </a>
              </li>
              <?php endif; ?>
              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?php echo $i == $current_page ? 'active' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
              <?php endfor; ?>
              <?php if ($current_page < $total_pages): ?>
              <li class="page-item">
                  <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" aria-label="Next">
                      <span aria-hidden="true">&raquo;</span>
                  </a>
              </li>
              <?php endif; ?>
          </ul>
      </nav>

            
            
            
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