<?php

$asd = require_once __DIR__."/../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php";

$objZoho=new ZOHO_METHODS();

$campname = array();

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
// echo "<pre/>";
// print_r($campname); die();
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
                     ?>
                    <?php foreach ($campname as $k => $cm) {  ?>
                            <p style="text-align: center;">
                              <?php $camp = explode('-', $cm); 
                                    $campid = $camp[1];
                                    $campsportname = $camp[2];
                              echo $camp[0] .' ('.$campsportname .')'; ?>
                                
                              </p>
                            <div class="table-responsive">
                              <table class="table table-striped jambo_table bulk_action" style="border: 1px solid black;">
                                <thead>
                                  <tr class="headings">
                                    <th>GRP</th>
                                    <th class="column-title">Coach</th>
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
                                          
                                          // $Camprec=$objZoho->getRecordById("Camps","3108913000057991153");
                                          $Camprec=$objZoho->getRelatedRecords("Camps",$campid,'Hired_Coaches16');
                                          
                                         echo  $totalcoach = $Camprec['info']['count'];
          
                                          // echo "<pre/>";
                                          // print_r($Camprec);
                                          $i = 1;
                                          foreach ($Camprec['data'] as $hk => $hiredcoach) 
                                          { ?>
                                              <tr class="even pointer">
                                                <td class="a-center "><?php echo $i; ?></td>
                                                <td class=" "><?php echo $hiredcoach['Hired_Coaches']['name'];?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
                                                <td class=" "><?php echo '1'; ?></td>
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
                   <?php } ?>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
	</body>
</html>