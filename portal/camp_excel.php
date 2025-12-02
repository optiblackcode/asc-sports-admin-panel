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


function SetName($val){
	$name = '';
	if($val == 1){
		$name = "Games";
	}
	else if($val == 2){
		$name = "Match Play";
	}
	else if($val == 3){
		$name = "Passing";
	}
	else if($val == 4){
		$name = "Shooting";
	}
	else if($val == 5){
		$name = "Dribbling";
	}
	else if($val == 6){
		$name = "First Touch and Control";
	}
	else if($val == 7){
		$name = "Attacking";
	}
	else if($val == 8){
		$name = "Defending";
	}
	else if($val == 9){
		$name = "Heading and Volleying";
	}
	else if($val == 10){
		$name = "Penalties";
	}
	else if($val == 11){
		$name = "Shooting Volleys";
	}
	else if($val == 12){
		$name = "Lofted Passing and Curling";
	}
	else{
		$name = $val;
	}
	
	return $name;
}


	try{
		if($objZoho->checkTokens()){
			$criteria="((Season:equals:Autumn) and (Year:equals:2022))";
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
		
		while($i <= 3){
			try{
				if($objZoho->checkTokens()){
					$Camprec=$objZoho->getRelatedRecords("Camps",$camp_id,'Hired_Coaches16');
          
					$rowCount =5; 
					$timeslotarr = array('9:00 to 9:20','9:20 to 10:10','10.10 to 11.00','11.00 to 11.50','11.50 to 12.30','12.30 to 1.10','1.10 to 2.00','2.00 to 2.50','2.50 to 3.00');
					$vpd1 = array('E','F','I','J');
					$vpd2 = array('D','E','F','I','J');
					$objWorkSheet = $objPHPExcel->createSheet($i); 
					$objPHPExcel->setActiveSheetIndex($i); 
					$j = 1;
					$coachcounter=0;
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
							header("Location: http://31.220.55.121/portal/camp_excel.php?err=Duplicate group number assign to coaches!");
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
					if(!empty($Camprec['data'])){
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
								$objPHPExcel->getActiveSheet()->getStyle('H14:H18')->applyFromArray($coachessStyleArray);
								$objPHPExcel->getActiveSheet()->getStyle('I14:I17')->applyFromArray($coachessStyleArray);
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
								$objPHPExcel->getActiveSheet()->getStyle("A1:O99")->applyFromArray($alignment);
								$objPHPExcel->getActiveSheet()->getStyle("A1:O99")->getFont()->setSize(18);
								$objWorkSheet->setCellValue('E1','Camp Name - '.$hiredcoach['Camps']['name'])->setCellValue('F2', 'Day - '.$i)->setCellValue('A4', 'GRP')->setCellValue('B4', "COACH");
                                $d = 'C';
                                foreach ($timeslotarr as $key) { 
                                    $objWorkSheet->setCellValue($d.'4',$key); 
                                    $timeslot[] = $key;
									$d++;
                                }
								$objPHPExcel->getActiveSheet()->getStyle('A1:R1')->applyFromArray(
                                    array(
                                        'fill' => array(
                                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                            'color' => array('rgb' => 'D3D3D3')
                                        )
                                    )
                                );
								$coachessStyleArray_new = array(
								  'borders' => array(
									'allborders' => array(
									  'style' => PHPExcel_Style_Border::BORDER_THIN
									)
								  )
								);
								$objPHPExcel->getActiveSheet()->getStyle('B26:B27')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('B29:B30')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('B32:B33')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('C26:C27')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('G26:G28')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('G30')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('H26:H28')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('H30')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('K26')->applyFromArray($coachessStyleArray_new);
								$objPHPExcel->getActiveSheet()->getStyle('L26')->applyFromArray($coachessStyleArray_new);
								$objWorkSheet->setCellValue('A'.$rowCount, $j);
								
								
								
								
										   
								if($i == 1){
								  $objWorkSheet->setCellValue('C'.$rowCount,SetName($sportsDataObj->getSportDataByID(4,0)));
								  if($sportsDataObj->getSportDataByID(4,0) != ''){
									$objPHPExcel->getActiveSheet()->getStyle('C14:C18')->applyFromArray($coachessStyleArray_new);
								  }
								}else{
								  $objWorkSheet->setCellValue('C'.$rowCount,SetName($sportsDataObj->getSportDataByID(4,0)));
								  if($sportsDataObj->getSportDataByID(4,0) != ''){
									$objPHPExcel->getActiveSheet()->getStyle('C14:C18')->applyFromArray($coachessStyleArray_new);
								  }
								}
                                if($i == 1){
                                  if($sportsDataObj->getSportDataByID(4,0) != ''){
                                    $objPHPExcel->getActiveSheet()->getStyle('D14:D20')->applyFromArray($coachessStyleArray_new);
                                  }
                                }else{
                                  $objWorkSheet->setCellValue('D'.$rowCount,'');
                                  if($sportsDataObj->getSportDataByID(4,0) != ''){
                                    $objPHPExcel->getActiveSheet()->getStyle('D14:D20')->applyFromArray($coachessStyleArray_new);
                                  }
                                }
                                if($i == 1 || $i == 2){
									$objWorkSheet->setCellValue('E'.$rowCount,SetName($sportsDataObj->getSportDataByID(8,0)));
								}
      
                               /* if($totalcoach > 3){*/
                                    $persontoLunch = ($totalcoach/2);
                                    $calcStudent = round($persontoLunch);
                                    $remianingStudent = $totalcoach - round($persontoLunch);
                                    $rendercount  = 5 + $calcStudent; 
                                    if($rowCount < $rendercount){
                                      $objWorkSheet->setCellValue('G'.$rowCount,'L');
										if($i == 1){
											//$objWorkSheet->setCellValue('L'.$rowCount,'T');
										}
                                    }else{
                                      $objWorkSheet->setCellValue('H'.$rowCount,'L');
										if($i == 1){
											//$objWorkSheet->setCellValue('M'.$rowCount,'T');
										}
                                    }
                                /*}else{
                                    $objWorkSheet->setCellValue('H'.$rowCount,'L');
									if($i == 1){
										// $objWorkSheet->setCellValue('M'.$rowCount,'T');
                                    } 
                                }*/
                                if($i != 1){
									$objWorkSheet->setCellValue('I'.$rowCount,SetName('1'));
                                }
                                if($i == 1){
									$objWorkSheet->setCellValue('J'.$rowCount,SetName('1'));
                                }else{
									$objWorkSheet->setCellValue('J'.$rowCount,SetName('2'));
                                } 
								$objWorkSheet->setCellValue('K'.$rowCount,SetName($sportsDataObj->getSportDataByID(1,0)));
                                $lastColumn = "K";
                                $lastColumn++;
                                if($i==1){ 
                                    $rowc1=5;
                                    $counter1 = 0; 
                                    foreach($vpd1 as $vprow1){
                                        if($counter1 === $totalcoach){
                                          break;
                                        }
                                        $cell1 = $objWorkSheet->getCell($vprow1.$rowCount);
                                        if($cell1!='L'){
											$objWorkSheet->setCellValue($vprow1.$rowc1,SetName($sportsDataObj->getSportDataByID(5,0)));
											$rowc1++;
											$counter1++;
                                        }   
                                    } 
                                }
                                if($i==2){
                                    $rowc2=11;
                                    $counter2 = $counter1; 
                                    foreach($vpd2 as $vprow2){
                                        if($counter2 == $totalcoach){
                                            break;
                                        }
                                        $cell2 = $objWorkSheet->getCell($vprow2.$rowCount);
                                        if($cell2!='L'){
                                            $objWorkSheet->setCellValue($vprow2.$rowc2,SetName($sportsDataObj->getSportDataByID(5,0)));
                                            $rowc2++;
                                            $counter2++;
                                          
                                        }      
                                    }      
                                }
                                if($i==3){
                                    $rowc3=17;
                                    $counter3 = $counter2; 
                                    foreach($vpd2 as $vprow3){
                                        if($counter3 == $totalcoach){
                                            break;
                                        }
                                        $cell3 = $objWorkSheet->getCell($vprow3.$rowCount);
                                        if($cell3!='L'){
                                            $objWorkSheet->setCellValue($vprow3.$rowc3,SetName($sportsDataObj->getSportDataByID(5,0)));
                                            $rowc3++;
                                            $counter3++; 
                                        }        
                                    }      
                                }
                                if($totalcoach == 7){
                                    $objWorkSheet->setCellValue('J8','L');
                                }
                                if($i==1){
                                
                                  while($row = $sport_act_result->fetch_array())
                                  {
                                  $rows[] = $row;
                                  }
                                  
                                  $c1=2;
                                  $day1=$rows[$c1]['activity_sort_name'];
                                  $day1rowslast = array();
                                  
                                  for ($column = 'A'; $column != $lastColumn; $column++) {
                                      $cell = $objWorkSheet->getCell($column.$rowCount);
                                     
                                      if($cell==''){
                                          $objWorkSheet->setCellValue($column.$rowCount,SetName($day1));
                                          $day1++;
                                          $c1++;
                                          
                                      }
                                       
                                      
                                  }
                                  $day1rowslast[$rowCount] = $day1-1;
                                  $day1LastArray = array_merge_values($day1LastArray,$day1rowslast);
                                 
                                 
                                }
                               
                               
                               
                                if($i==2){
                                 
                                  $day2 = $day1LastArray[$rowCount] + 1;
                                  
                                  $day1rowslast = array();
                                  for ($column = 'A'; $column != $lastColumn; $column++) {
                                      $cell = $objWorkSheet->getCell($column.$rowCount);
                                      $scount = count($rows) + 1;
                                      if($day2 == $scount){
                                        $c2=2;
                                        $day2=$rows[$c2]['activity_sort_name'];
                                      }
                                      if($cell==''){
                                          $objWorkSheet->setCellValue($column.$rowCount,SetName($day2));
                                          $day2++;
                                          $c2++;
                                      }
                                     
                                    
                                  }
                                  $day2rowslast[$rowCount] = $day2-1;
                                  
                                  $day2LastArray = array_merge_values($day2LastArray,$day2rowslast);
                                  
                                }
                                
                                if($i==3){
                                 
                                  $day3 = $day2LastArray[$rowCount] + 1;
                                  
                                  $day3rowslast = array();
                                  for ($column = 'A'; $column != $lastColumn; $column++) {
                                      $cell = $objWorkSheet->getCell($column.$rowCount);
                                      $scount = count($rows) + 1;
                                      if($day3 == $scount){
                                        $c2=2;
                                        $day3=$rows[$c2]['activity_sort_name'];
                                      }
                                      if($cell==''){
                                          $objWorkSheet->setCellValue($column.$rowCount,SetName($day3));
                                          $day3++;
                                          $c2++;
                                      }
                                      
                                    
                                  }
                                  $day3rowslast[$rowCount] = $day3-1;
                                  $day3LastArray = array_merge_values($day3LastArray,$day3rowslast);
                                
                                }
                                $dd1= 14;//$totalcoach + 7;
                                $ac1=1;
                                $dd2= 14;//$totalcoach + 7;
                                $ac2=6;
                                foreach($rows as $activity){
                                  if($ac1 < 6){
                                   // $objWorkSheet->setCellValue("C".$dd1,$ac1.' = '.$activity['activity']);
                                    $ac1++;$dd1++;
                                  }else{
                                   // $objWorkSheet->setCellValue("D".$dd2,$ac2.' = '.$activity['activity']);
                                    $dd2++;
                                    $ac2++;
                                  }
                                 
                                }  
                               /* $objWorkSheet->setCellValue("H14","V&P = Video Record & PB");
                                $objWorkSheet->setCellValue("H15","T = Talent");
                                $objWorkSheet->setCellValue("H16","FT = Fittness Test");
                                $objWorkSheet->setCellValue("H17","L = Lunch");
                                $objWorkSheet->setCellValue("H18","SO = SignOut");
                                $objWorkSheet->setCellValue("I14","P = Presentation");
                                $objWorkSheet->setCellValue("I15","W = Welcome");
                                $objWorkSheet->setCellValue("I16","WU = Warm up");
                                $objWorkSheet->setCellValue("I17","D = Drinks");*/
                                $coachessStyleArr = array(
								  'borders' => array(
									'allborders' => array(
									  'style' => PHPExcel_Style_Border::BORDER_THIN
									)
								  )
								);
								$objPHPExcel->getActiveSheet()->getStyle('H14:H18')->applyFromArray($coachessStyleArr);
								$objPHPExcel->getActiveSheet()->getStyle('I14:I17')->applyFromArray($coachessStyleArr);
                                //$objWorkSheet->setCellValue("A14","NOTE:");
                               // $objWorkSheet->setCellValue("B14","NO DESIGNATED DRINKS BREAKS, COACHES TO INCORPORATE DRINKS BREAKS INTO 50 MIN COACHING BLOCKS");
                                //$objWorkSheet->setCellValue("B26","ASC COS");
                                //$objWorkSheet->setCellValue("B27",$cos);
                                //$objWorkSheet->setCellValue("B29","First Aid");
                                //$objWorkSheet->setCellValue("B30",$fa);
                                //$objWorkSheet->setCellValue("B32","Video");
                               // $objWorkSheet->setCellValue("B33",$video);
                               // $objWorkSheet->setCellValue("C26","ASC Admin Office");
                               // $objWorkSheet->setCellValue("C27","1300-914-368");
                               // $objWorkSheet->setCellValue("G26",$sport);
                               // $objWorkSheet->setCellValue("G27","Coaching Director");
                               // $objWorkSheet->setCellValue("G28",$cd);
                               // $objWorkSheet->setCellValue("G30","Talent");
                              //  $objWorkSheet->setCellValue("G31",$talent);
                               // $objWorkSheet->setCellValue("K26","Notes and Important info:");			
								$objWorkSheet->setTitle("Day_".$i);
								$rowCount++;
								$j++;
								$cnc++;
							}
						}
					}else{
					  $err= "No coaches available for selected camp";
					  
					}
           
       
				}else{
					$crmLog.=", Token-Error";
					$success=false;
				}
			}
			catch(Exception $e){
				$crmLog.="Exception : ".$e->getMessage().", ";
				$success=false;
			}
			$i++;
    }
    
    
   
    $objPHPExcel->setActiveSheetIndexByName('Worksheet');
    $sheetIndex = $objPHPExcel->getActiveSheetIndex(0);
   $objPHPExcel->removeSheetByIndex($sheetIndex);
    //Border for cell
    
    
    
    // End
    // Font Size and Font Weight for Camp Name title 
    

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$camp_name.'.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    
    
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
                      
                      <form id="frmShedule" class="form-horizontal form-label-left" method="POST" enctype="multipart/form-data"  >
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
    
    
      $(document).ready(function(){
        $('#frmShedule').submit(function() {
          $('#loading').show(); 
          return true;
        });

        setTimeout(function(){ $('#loading').hide();  }, 40000);


      });
      
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
