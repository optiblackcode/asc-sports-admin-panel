<?php
ini_set('max_execution_time', 10800); // 3 Hour
ini_set("memory_limit", "-1");
set_time_limit(0);


$host="localhost";
$username="root";
$password="iRg7QOwKmTdO10mB";
$db="asc_datastudio_reporting";

$conn = new mysqli($host, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM sports_activity ";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$asd = require_once __DIR__."/../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php";

$objZoho=new ZOHO_METHODS();

$campname = array();
$timeslot = array();
$campsports = [];

try
  {
      if($objZoho->checkTokens())
      {

        $criteria="((Status:equals:Current) and (Is_Partner_Program:equals:No) and (Business_Arm:equals:ASC))";

        $arrParams['criteria']=$criteria;

        $rsltCamp=$objZoho->searchRecords("Camps",$arrParams);

        // echo "<pre/>";
        // print_r($rsltCamp);

        foreach ($rsltCamp['data'] as $key => $value) 
        {
          $campname[] = $value['Product_Name'] .'-'. $value['id'] .'-'. $value['Sports']['name'];
          $campsports[] = $value['Sports']['name'];
        }

      }
      else
      {
        $crmLog.=", Token-Error";
        $success=false;
      }
  }
  catch(Exception $e)
  {
      $crmLog.="Exception : ".$e->getMessage().", ";
      $success=false;
  }
echo "<pre/>";
print_r($campsports); die();


?>
<!DOCTYPE html>
<html lang="en">
<style>
  table,
  th,
  td {
    padding: 10px;
    border: 1px solid black;
    border-collapse: collapse;
  }
</style>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="right_col" role="main" style="min-height: 1161px;">
          <div class="">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <?php // print_r($campname);

                      $timeslotarr = array('9:00 to 9:10','9:15 to 9:45','9:45 to 10:15','10:15 to 10:45','10:45 to 11:00','11:00 to 11:30','11:30 to 12:00','12:00 to 12:45','12:45 to 1:15','1:15 to 1:55','1:55 to 2:30','2:30 to 3:00','3');
                      $k=1;
                      while($k <= 3)
                      {
                     ?>
                    <?php // foreach ($campname as $k => $cm) {  ?>
                             <!-- <p style="text-align: center;">
                              <?php $camp = explode('-', $cm); 
                                    $campid = $camp[1];
                                    $campsportname = $camp[2];
                              echo $camp[0] .' ('.$campsportname .')'; ?>
                                
                              </p> -->
                            <div class="table-responsive">
                              <table class="table table-striped jambo_table bulk_action" style="border: 1px solid black;">
                                <thead>
                                  <tr>
                                    <th colspan="15" style="text-align: center;"><?php echo 'Day - '.$k .' ( Basketball )'; ?></th>
                                  </tr>
                                  <tr>
                                    <th colspan="15" style="text-align: center;"><?php echo 'NSW Basketball Camp, Ryde - Aut 2021'; ?></th>
                                  </tr>
                                  
                                </thead>
                                <thead>
                                  <tr class="headings">
                                    <th>GRP</th>
                                    <th class="column-title">Coach</th>
                                    <th class="column-title">CoachName</th>
                                    <th class="column-title">Role</th>
                                    <?php 
                                    $j = 'C';
                                    foreach ($timeslotarr as $key) { ?>
                                      
                                      <th class="column-title"><?php echo $j.'-'.$key ?></th>

                                    <?php  $j++;
                                    } ?>
                                    
                                    
                                  </tr>
                                </thead>

                                <tbody>
                                <?php try
                                      {
                                        if($objZoho->checkTokens())
                                        {
                                          
                                          // $criteria1="((Status:equals:Current) and (Is_Partner_Program:equals:No) and (Business_Arm:equals:ASC))";

                                          // $arrParams1['criteria']=$criteria1;

                                          $Camprec=$objZoho->getRelatedRecords("Camps","3108913000059188155",'Hired_Coaches16');
                                          // $Camprec=$objZoho->getRelatedRecords("Camps","3108913000058033108",'Hired_Coaches16');

                                           
                                          // $Camprec=$objZoho->getRelatedRecords("Camps",$campid,'Hired_Coaches16');
                                          // echo "<pre/>";
                                          // print_r($Camprec); die();
                                         $totalcoach = $Camprec['info']['count'];
          
                                          // echo "<pre/>";
                                          // print_r($Camprec); die();
                                          $i = 1;
                                          foreach ($Camprec['data'] as $hk => $hiredcoach) 
                                          { 
                                            $coachid =  $hiredcoach['Hired_Coaches']['id']; 
                                           
                                           $Campreccoachname=$objZoho->getRecordById("Hired_Coaches",$coachid);
                                            // echo "<pre/>";
                                            // print_r($Campreccoachname); die();

                                            ?>
                                              <tr class="even pointer">
                                                <td class="a-center "><?php echo $i; ?></td>
                                                <td class=" "><?php echo $hiredcoach['Hired_Coaches']['name'];?></td>
                                                <td class=" "><?php echo $Campreccoachname['data'][0]['Coach_Name']['name'];?></td>
                                                <td class=" "><?php echo $Campreccoachname['data'][0]['Role'][0];?></td>
                                              <?php  if($k == 1){ ?>
                                                      <td class=" "><?php echo getColumnData($conn,3,0);?></td>
                                                    <?php  }else{ ?>
                                                      <td class=" "><?php echo getColumnData($conn,4,0);?></td>
                                                    <?php  } ?>


                                                    <?php  if($k == 1){ ?>
                                                      <td class=" "><?php echo getColumnData($conn,4,0);?></td>
                                                    <?php  }else{ ?>
                                                      <td class=" "><?php echo "";?></td>
                                                    <?php  } ?>

                                                <td class=" "><?php echo ''; ?></td>
                                                <td class=" "><?php echo ''; ?></td>
                                                
                                                <td class=" "><?php echo getColumnData($conn,6,0); ?></td>
                                                <td class=" "><?php echo ''; ?></td>
                                                <td class=" "><?php echo ''; ?></td>
                                                <td class=" "><?php echo ''; ?></td>
                                                <td class=" "><?php echo ''; ?></td>
                                                <td class=" "><?php echo ''; ?></td>
                                                
                                                <?php  if($k != 1){ ?>
                                                      <td class=" "><?php echo "1";?></td>
                                                    <?php  }else{ ?>
                                                      <td class=" "><?php echo "";?></td>
                                                    <?php  } ?>

                                                  <?php  if($k == 1){ ?>
                                                      <td class=" "><?php echo "1";?></td>
                                                    <?php  }else{ ?>
                                                      <td class=" "><?php echo "2";?></td>
                                                    <?php  } ?>


                                                 <?php  if($k == 1 || $k == 2){ ?>
                                                      <td class=" "><?php echo getColumnData($conn,1,0);?></td>
                                                    <?php  }else{ ?>
                                                      <td class=" "><?php echo getColumnData($conn,2,0);?></td>
                                                    <?php  } ?>
                                                
                                              </tr>
                                        <?php $i++;  }
                                        }
                                        else
                                        {
                                          $crmLog.=", Token-Error";
                                          $success=false;
                                        }
                                    }
                                    catch(Exception $e)
                                    {
                                        $crmLog.="Exception : ".$e->getMessage().", ";
                                        $success=false;
                                    } ?>
                                </tbody>
                              </table>
                            </div>
                   <?php // } 
                      $k++;
                      }
                   ?>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
  </body>
</html>
<?php

function getColumnData($conn,$rec_id,$activity_type){
 
   $sql = "SELECT activity_sort_name FROM sports_activity WHERE rec_id=".$rec_id." AND activity_type=".$activity_type."";

   $result = $conn->query($sql);
   $row = $result->fetch_assoc();
   return $row['activity_sort_name'];
}

?>