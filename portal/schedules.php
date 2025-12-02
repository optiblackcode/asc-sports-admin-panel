<?php
ini_set('max_execution_time', 10800); // 3 Hour
ini_set("memory_limit", "-1");
set_time_limit(0);
include "include/common.php";
require_once 'libraries/Classes/PHPExcel.php';
require_once 'libraries/Classes/PHPExcel/IOFactory.php';
require_once __DIR__."/../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php";



$objZoho=new ZOHO_METHODS();
$campname = array();
$timeslot = array();

	try{
		if($objZoho->checkTokens()){
			$criteria="((Season:equals:Summer) and (Year:equals:2023))";
			$arrParams['criteria']=$criteria;
			$rsltCamp=$objZoho->searchRecords("Camps",$arrParams);
		}
		else{
			$crmLog.=", Token-Error";
			$success=false;
		}
	}
	catch(Exception $e){
	  $crmLog.="Exception : ".$e->getMessage().", ";
	  $success=false;
	}
	
	$objPHPExcel = new PHPExcel();
	$loaderHide= 0 ;
	
	if(isset($_POST['submit'])){
		$campData = explode('|',$_POST['camp']);
		$camp_id = $campData[0];
		$sport = ($campData[1] == 'AFL/AFLW Football') ? 'AFL': $campData[1];
		$camp_name = $campData[2];
		$sportsDataObj = new SPORTS_ACTIVITY_DATA();
		$sport_act_result = $sportsDataObj->getSportActivityBySport($sport);
		$i=1;
		$day1LastArray= array();
		$day2LastArray= array();
		$day3LastArray= array();
		
		
		
		function get_sport($name,$sort){
			$host="localhost";
			$username="RBUH9jPnna";
			$password="BYdxh5JIu!";
			$db="asc_datastudio_reportingnew";
			$con = mysqli_connect("localhost",$username,$password,$db);
			$qrySel="SELECT * FROM `sports_activity` WHERE  `sports_name`='".$name."' AND `activity_sort_name` = '".$sort."'";
			$result = mysqli_query($con, $qrySel);
			$data = mysqli_fetch_assoc($result);
			return $data['activity'];
		}
		
		try{
			if($objZoho->checkTokens()){
				$coachcounter=0;
				$Camprec=$objZoho->getRelatedRecords("Camps",$camp_id,'Hired_Coaches16');
				if(!empty($Camprec['data'])){
					$coachNameArray = [];
					foreach ($Camprec['data'] as $hk => $hiredcoach){
						$coachid =  $hiredcoach['Hired_Coaches']['id']; 
						$Campreccoachname=$objZoho->getRecordById("Hired_Coaches",$coachid);
						$coachName = $Campreccoachname['data'][0]['Coach_Name']['name'];
						if(in_array('Group Coach', $Campreccoachname['data'][0]['Role']) || in_array('Senior Coach', $Campreccoachname['data'][0]['Role'])) {
							if($Campreccoachname['data'][0]['Group_Number']!=''){
								$coachNameArray[] = array('name'=> $coachName,'groupNum'=>$Campreccoachname['data'][0]['Group_Number']);
							}else{
								$coachNameArray[] = array('name'=> $coachName,'groupNum'=>'l');
							}
							$coachcounter++;
						}
					}
					$sortCoaches = array_column($coachNameArray, 'groupNum');
					array_multisort($sortCoaches, SORT_ASC, $coachNameArray);
					$cm = array_column($coachNameArray, 'groupNum');
					if($cm != array_unique($cm)){
						header("Location: http://31.220.55.121/portal/camp_excel.php?err=Duplicate group number assign to coaches");
						die();
					}
					$rs=5;
					foreach ($coachNameArray as $sortval){
						$objWorkSheet->setCellValue('B'.$rs,$sortval['name']);
						$rs++;
					}	
				}
				$totalcoach = $coachcounter;
				if($totalcoach == 0){
					header("Location: http://31.220.55.121/portal/camp_excel.php?err=No Coaches assign to this camp yet!");
					die();
				}
				echo $coachcounter;
				exit;
				if($coachcounter < 5){
					if(!empty($Camprec['data'])){
						$i = 0;
						$objWorkSheet = $objPHPExcel->createSheet($i); 
						$objPHPExcel->setActiveSheetIndex($i); 
						
						for ($x = 0; $x <= 3; $x++) {
							$cnc =  15;
							$cos = '';
							$Fa='';
							$Cd='';
							$talent='';
							foreach ($Camprec['data'] as $hk => $hiredcoach){ 
								$coachid =  $hiredcoach['Hired_Coaches']['id']; 
								$Campreccoachname=$objZoho->getRecordById("Hired_Coaches",$coachid);
								$coachName = $Campreccoachname['data'][0]['Coach_Name']['name'];
								if(in_array('Chief of Staff', $Campreccoachname['data'][0]['Role'])){
								  $cos = $coachName;
								}
								if(in_array('First Aid', $Campreccoachname['data'][0]['Role'])){
								  $fa = $coachName;
								}
								if(in_array('Video', $Campreccoachname['data'][0]['Role'])){
								  $video = $coachName;
								}
								if(in_array('Coaching Director', $Campreccoachname['data'][0]['Role'])){
								  $cd = $coachName;
								}
								if(in_array('Talent', $Campreccoachname['data'][0]['Role'])){
								  $talent = $coachName;
								}
								if (in_array('Group Coach', $Campreccoachname['data'][0]['Role']) || in_array('Senior Coach', $Campreccoachname['data'][0]['Role'])) {	
									$styleArray = array(
									  'borders' => array(
										'allborders' => array(
										  'style' => PHPExcel_Style_Border::BORDER_THIN
										)
									  )
									);
									$borderlastcell = $totalcoach+4;
									$objPHPExcel->getActiveSheet()->getStyle('A4:k'.$borderlastcell)->applyFromArray($styleArray);
									$coachessStyleArray = array(
									  'borders' => array(
										'allborders' => array(
										  'style' => PHPExcel_Style_Border::BORDER_THIN
										)
									  )
									);
									$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
									$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
									$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
									$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
									$lastColumn = $objWorkSheet->getHighestColumn();
									$fontStyle = array(
									  'font' => array(
										  'size' => 16,
										  'bold' => true
									  )
									);
									$objPHPExcel->getActiveSheet()->getStyle("E1")->applyFromArray($fontStyle);
									$fontStyleforAll = array(
									  'font' => array(
										  'bold' => true
									  )
									);
									$objPHPExcel->getActiveSheet()->getStyle("A2:O40")->applyFromArray($fontStyleforAll);
									$objPHPExcel->getActiveSheet()->getStyle('A4:O99')->getAlignment()->setWrapText(true);
									$alignment = array(
										'alignment' => array(
											'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
										)
									);
									
									if($x == 1){
										$e = 1;
										$f = 2;
										$a = 4;
									}
									if($x == 2){
										$e = 8;
										$f = 9;
										$a = 12;
									}
									if($x == 3){
										$e = 16;
										$f = 17;
										$a = 20;
									}
									
									$objPHPExcel->getActiveSheet()->getStyle("A1:O99")->applyFromArray($alignment);
									$objPHPExcel->getActiveSheet()->getStyle("A1:O99")->getFont()->setSize(18);
									$objWorkSheet->setCellValue('E'.$e,'Camp Name - '.$hiredcoach['Camps']['name'])->setCellValue('F'.$f, 'Day - '.$x)->setCellValue('A'.$a, 'GRP')->setCellValue('B4', "COACH");
									$d = 'C';
									foreach ($timeslotarr as $key) { 
										$objWorkSheet->setCellValue($d.'4',$key); 
										$timeslot[] = $key;
										$d++;
									}
									echo "here"; 
									
									$objWorkSheet->setTitle("Day_");
									
								}
							}
						} 
						$objPHPExcel->setActiveSheetIndexByName('Worksheet');
						$sheetIndex = $objPHPExcel->getActiveSheetIndex(0);
						$objPHPExcel->removeSheetByIndex($sheetIndex);
					
					

						header('Content-Type: application/vnd.ms-excel');
						header('Content-Disposition: attachment;filename="'.$camp_name.'.xls"');
						header('Cache-Control: max-age=0');
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
						$objWriter->save('php://output');
						
					}
					else{
					  $err= "No coaches available for selected camp"; 
					}
				}
				else{
					
				}
				
				
				
				
			
			}
			else{
				$crmLog.=", Token-Error";
				$success=false;
			}
		}
		catch(Exception $e){
			$crmLog.="Exception : ".$e->getMessage().", ";
			$success=false;
		}
		
    
   
   
		
    
    
	}

function array_merge_values()
{
  $args = func_get_args();

  $result = $args[0];
  for ($_ = 1; $_ < count($args); $_++)
    foreach ($args[$_] as $key => $value)
    {
      if (array_key_exists($key,$result))
        $result[$key] = $value;
      else
        $result[$key] = $value;
    }
  return $result;
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
        max-width: 200px;
        max-height: 200px;
      }
      .error{
        color:#ff0000;
      }
      #iframePreview, #loader{
        border:1px solid #f1f1f1;
      }
      #loader,#form_loader{
        text-align: center;
        margin:auto;
        padding-top:100px;
      }
      #form_loader{
        position: absolute;
        z-index: 10;
      }
      div#loading {
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          position: fixed;
          display: none;
          opacity: 0.7;
          background-color: #fff;
          z-index: 99;
          text-align: center;
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
		<div>
		
			<div class="right_col" role="main" style="min-height: 3573px;">
          <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Camp Schedules</h3>
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
                    <div class="row">
                    <?php if(isset($_GET['err'])){?>
                      <div class="alert alert-danger fade in alert-dismissible">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                        <strong>Error!</strong> <?php echo $_GET['err']; ?>
                      </div>
                    <?php } ?>
                    <div class="col-md-8">
                      
                      <form id="frmShedule" action="schedules_process.php" class="form-horizontal form-label-left" method="POST" enctype="multipart/form-data"  >
                          <div id="loading">
                          <img src="images/giphy.gif" alt="loader" style="display:block; position:absolute; z-index:100; left:50%; top:100px" id="loaderImg">
                          </div>
                          <input type="hidden" value="preview" id="hdnAction" name="hdnAction" />
                          <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="camp">Camp : 
                                  </label>
                                  <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                    <b></b>
                                    <span class="error"><?php echo $err;?></span>
                                    <select class="form-control select2-js" name="camp" id="camp">
                                      <option value="">--Select Camp--</option>
                                      <?php
                                        foreach ($rsltCamp['data'] as $campData) 
                                        {
                                      ?>
                                          <option value="<?php echo $campData['id'].'|'.$campData['Sports']['name'].'|'.$campData['Name'];?>"><?php echo $campData['Name'];?></option>
                                      <?php
                                         }
                                      ?>
                                    </select>
                                    <span class="error"><?php echo $arrErrors['name'];?></span>
                                  </div>
                              </div>
                          
                        </div>
                        
                          <div class="form-group">
                            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                              <button class="btn btn-primary" type="submit" onClick="return fnValidation('preview');" name="submit">Generate</button></a>
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
		</div>
		<?php 
			// Include common footer for all pages
			include "include/common_footer.php";
		?>
    <script type="text/javascript">
    
    
     
      
      function fnValidation(action){
        $("#hdnAction").val(action);
        $(".form-group .error").html("");

        var camp=$("#camp").val();
      

        var blError=false;
        if(camp.trim()=="")
        {
          var msg="Please select camp.";
          $("#camp").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        
        if(blError)
        {
          return false;
        }
        return true;
      }
    </script>
	</body>
</html>


