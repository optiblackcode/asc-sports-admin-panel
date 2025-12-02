<?php
require_once "../includes/conn.php"; 
include("../zoho/Zoho.php");
$objZoho = new Zoho();
if(isset($_POST)){

     $service = $_POST['userData'];

     foreach ($service as $key => $value) {
          $query ="INSERT INTO maintain_service_data (select_vehical_id, datepicker_select_date, location,services,vendor_name) VALUES ( '". $_POST['vehical_data']."','".$value['datepicker']."','".$value['location']."','".$value['services']."','".$value['vendor_name']."' )";
               $results =  mysqli_query($con, $query);
                    $servicess          = $value['services'];
                    $vendor_names       = $value['vendor_name'];
                    $vehical_datas      = $_POST['vehical_data'];
               $search_service = "select vendor_zoho_id from vendor_type WHERE `id` = '$servicess'";

               $querys = mysqli_query($con, $search_service);

               $service_row = mysqli_fetch_array($querys);

               $search_vendor = "select ven_id from vendor_data WHERE `vendor_data_id` = '$vendor_names'";

               $vendor_querys = mysqli_query($con, $search_vendor);

               $vendor_row = mysqli_fetch_array($vendor_querys);


               $search_vehical = "select id from vehicle WHERE `auto_id` = '$vehical_datas'";

               $vehical_querys = mysqli_query($con, $search_vehical);

               $vehical_row = mysqli_fetch_array($vehical_querys);

               
               if ($objZoho->checkTokens()) {
                    $vehical__id = $vehical_row['id'];
                    $criteria="(Vehicle_ID_R:equals:".$vehical__id.")";
                    $arrParams['criteria']=$criteria;
                    $RespLeads=$objZoho->searchRecords("Vehicles",$arrParams);

                    // echo "<pre>";
                    // print_r($RespLeads['data'][0]['id']);
                    // die();


                    $dates = $value['datepicker'];
                    $date = str_replace('/', '-', $dates);
                    $format = date('Y-m-d', strtotime($date));

                    $vehical_data      = $RespLeads['data'][0]['id'];
                    $location          = $value['location'];
                    $services          = $service_row['vendor_zoho_id'];
                    $vendor_name       = $vendor_row['ven_id'];

                    $arr = ["Date" => $format,"Vehicle" => $vehical_data,"Location" => $location,"Vehicle" => $vehical_data,"Service" => $services,"Vendor" => $vendor_name];
                    $arrInsertLeads[] = $arr;
                    $arrTrigger = ["workflow"];
                    $respInsertLeads = $objZoho->insertRecord("Vehicle_Maintenance",$arrInsertLeads,$arrTrigger);

               }else{
                    echo "error";
               }
               if ($results == 1) {
                    session_start();
                    $_SESSION['success_message'] = "Saved successfully.";
                    header('location: /super_admin/maintaince_view.php');
               }else{
                    echo "Server problem, Try after sometime.";
               }
          }

     }

?>