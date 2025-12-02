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
			$criteria="((Season:equals:Autumn) and (Year:equals:2023))";
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
	
	//print_r($_POST);
	
	
	
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
		$timeslotarr = array('9:00 to 9:20','9:20 to 10:10','10.10 to 11.00','11.00 to 11.50','11.50 to 12.30','12.30 to 1.10','1.10 to 2.00','2.00 to 2.50','2.50 to 3.00');
		$vpd1 = array('E','F','I','J');
					$vpd2 = array('D','E','F','I','J');
		try{
			if($objZoho->checkTokens()){
				
				//echo "here";
				
				$coachcounter=0;
				$Camprec=$objZoho->getRelatedRecords("Camps",$camp_id,'Hired_Coaches16');
				
				
				
				
				if(!empty($Camprec['data'])){
					//echo "here";
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
						
						header("Location: http://31.220.55.121/portal/schedules.php?err=Duplicate group number assign to coaches");
						die();
					}
					
					
						
				}
				//echo $coachcounter;
				if($coachcounter < 5){
					if(!empty($Camprec['data'])){
						$h = 0;
					$objWorkSheet = $objPHPExcel->createSheet($h); 
					$objPHPExcel->setActiveSheetIndex($h); 
						//echo "aaa";
						
						for ($x = 1; $x <= 3; $x++) {
							//echo $x;
							$cnc =  15;
							$cos = '';
							$Fa='';
							$Cd='';
							$talent='';
							$totalcoach = $coachcounter;
							$j = 1;
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
									//echo "here";
									
									if($x == 1){
										$a1 = "A1";
										$e = "E1";
										$b = "B4";
										$f = "F2";
										$a = "A4";
										$dd = 4;
										$borderlastcell = $coachcounter+4;
										$rs=5;
										$rowCount =5; 
										$rowc1=5;
									}
									if($x == 2){
										$a1 = "A10";	
										$e = "E10";
										$f = "F11";
										$a = "A14";
										$b = "B14";
										$dd = 14;
										$borderlastcell = $coachcounter+14;
										$rs=15;
										$rowCount =15; 
										$rowc1=15;
									}
									if($x == 3){
										$a1 = "A18";
										$e = "E18";
										$f = "F19";
										$a = "A22";
										$b = "B22";
										$dd = 22;
										$borderlastcell = $coachcounter+22;
										$rs=23;
										$rowCount =23; 
										$rowc1=23;
									}
									
									
									
					
									foreach ($coachNameArray as $sortval){
										$objWorkSheet->setCellValue('B'.$rs,$sortval['name']);
										$rs++;
									}
									
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
									$styleArray = array(
									  'borders' => array(
										'allborders' => array(
										  'style' => PHPExcel_Style_Border::BORDER_THIN
										)
									  )
									);
									
									$objPHPExcel->getActiveSheet()->getStyle($a.':k'.$borderlastcell)->applyFromArray($styleArray);
									$coachessStyleArray = array(
									  'borders' => array(
										'allborders' => array(
										  'style' => PHPExcel_Style_Border::BORDER_THIN
										)
									  )
									);
									$fontStyle = array(
									  'font' => array(
										  'size' => 16,
										  'bold' => true
									  )
									);
									$objPHPExcel->getActiveSheet()->getStyle($e)->applyFromArray($fontStyle);
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
									
									$objWorkSheet->setCellValue($e,'Camp Name - '.$hiredcoach['Camps']['name'])->setCellValue($f, 'Day - '.$x)->setCellValue($a, 'GRP')->setCellValue($b, "COACH");
									
									$d = 'C';
									foreach ($timeslotarr as $key) { 
										$objWorkSheet->setCellValue($d.$dd,$key); 
										$timeslot[] = $key;
										$d++;
									}
									$objWorkSheet->setCellValue('A'.$rowCount, $j);
										   
									if($x == 1){
									  $objWorkSheet->setCellValue('C'.$rowCount,$sportsDataObj->getSportDataByID(4,0));
									  if($sportsDataObj->getSportDataByID(4,0) != ''){
										//$objPHPExcel->getActiveSheet()->getStyle('C14:C18')->applyFromArray($coachessStyleArray_new);
									  }
									}else{
									  $objWorkSheet->setCellValue('C'.$rowCount,$sportsDataObj->getSportDataByID(4,0));
									  if($sportsDataObj->getSportDataByID(4,0) != ''){
										//$objPHPExcel->getActiveSheet()->getStyle('C14:C18')->applyFromArray($coachessStyleArray_new);
									  }
									}
									if($x == 1){
									  if($sportsDataObj->getSportDataByID(4,0) != ''){
										//$objPHPExcel->getActiveSheet()->getStyle('D14:D20')->applyFromArray($coachessStyleArray_new);
									  }
									}else{
									  $objWorkSheet->setCellValue('D'.$rowCount,'');
									  if($sportsDataObj->getSportDataByID(4,0) != ''){
										//$objPHPExcel->getActiveSheet()->getStyle('D14:D20')->applyFromArray($coachessStyleArray_new);
									  }
									}
									if( $x == 2){
										//$objWorkSheet->setCellValue('E'.$rowCount,$sportsDataObj->getSportDataByID(8,0));
									}
									
									if($totalcoach > 3){
                                    $persontoLunch = ($totalcoach/2);
                                    $calcStudent = round($persontoLunch);
                                    $remianingStudent = $totalcoach - round($persontoLunch);
                                    $rendercount  = 5 + $calcStudent; 
                                    if($rowCount < $rendercount){
										  $objWorkSheet->setCellValue('G'.$rowCount,'Lunch');
											if($x == 1){
												//$objWorkSheet->setCellValue('L'.$rowCount,'T');
											}
										}else{
										  $objWorkSheet->setCellValue('H'.$rowCount,'Lunch');
											if($x == 1){
												//$objWorkSheet->setCellValue('M'.$rowCount,'T');
											}
										}
									}else{
										$objWorkSheet->setCellValue('H'.$rowCount,'Lunch');
										if($x == 1){
											// $objWorkSheet->setCellValue('M'.$rowCount,'T');
										} 
									}
									
									if($x != 1){
										$objWorkSheet->setCellValue('I'.$rowCount,'Games');
									}
									if($x == 1){
										$objWorkSheet->setCellValue('J'.$rowCount,'Games');
									}else{
										$objWorkSheet->setCellValue('J'.$rowCount,'Match Play');
									} 
									$objWorkSheet->setCellValue('K'.$rowCount,$sportsDataObj->getSportDataByID(1,0));
									$lastColumn = "K";
									$lastColumn++;
									if($x==2){
										
										$counter11 = 0;
										foreach($vpd1 as $vprow1){
											if($counter11 === $totalcoach){
											  break;
											}
											$cell1 = $objWorkSheet->getCell($vprow1.$rowCount);
											if($cell1!='L'){
												$objWorkSheet->setCellValue($vprow1.$rowc1,$sportsDataObj->getSportDataByID(8,0));
												$rowc1++;
												$counter11++;
											}   
										} 
									}
									if($x==1){ 
										
										$counter1 = 0; 
										foreach($vpd1 as $vprow1){
											if($counter1 === $totalcoach){
											  break;
											}
											$cell1 = $objWorkSheet->getCell($vprow1.$rowCount);
											if($cell1!='L'){
												$objWorkSheet->setCellValue($vprow1.$rowc1,$sportsDataObj->getSportDataByID(5,0));
												$rowc1++;
												$counter1++;
											}   
										} 
									}
									if($x==2){
										$rowc2=11;
										$counter2 = $counter1; 
										foreach($vpd2 as $vprow2){
											if($counter2 == $totalcoach){
												break;
											}
											$cell2 = $objWorkSheet->getCell($vprow2.$rowCount);
											if($cell2!='L'){
												$objWorkSheet->setCellValue($vprow2.$rowc2,$sportsDataObj->getSportDataByID(5,0));
												$rowc2++;
												$counter2++;
											  
											}      
										}      
									}
									if($x==3){
										$rowc3=17;
										$counter3 = $counter2; 
										foreach($vpd2 as $vprow3){
											if($counter3 == $totalcoach){
												break;
											}
											$cell3 = $objWorkSheet->getCell($vprow3.$rowCount);
											if($cell3!='L'){
												$objWorkSheet->setCellValue($vprow3.$rowc3,$sportsDataObj->getSportDataByID(5,0));
												$rowc3++;
												$counter3++; 
											}        
										}      
									}
									if($totalcoach == 7){
										$objWorkSheet->setCellValue('J8','L');
									}
									if($x==1){
									
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
											  
											  $resp = get_sport($sport,$day1);
											  
											  $objWorkSheet->setCellValue($column.$rowCount,$resp);
											  $day1++;
											  $c1++;
											  
										  }
										   
										  
									  }
									  $day1rowslast[$rowCount] = $day1-1;
									  $day1LastArray = array_merge_values($day1LastArray,$day1rowslast);
									 
									 
									}
									if($x==2){
									 
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
											   $resp = get_sport($sport,$day2);
											  $objWorkSheet->setCellValue($column.$rowCount,$resp);
											  $day2++;
											  $c2++;
										  }
										 
										
									  }
									  $day2rowslast[$rowCount] = $day2-1;
									  
									  $day2LastArray = array_merge_values($day2LastArray,$day2rowslast);
									  
									}
									
									if($x==3){
									 
									  $day3 = $day2LastArray[$rowCount] + 1;
									  
									  $day3rowslast = array();
									  for ($column = 'A'; $column != $lastColumn; $column++) {
										  $cell = $objWorkSheet->getCell($column.$rowCount);
										  $scount = count($rows) + 1;
										  if($day3 == $scount){
											$c3=2;
											$day3=$rows[$c3]['activity_sort_name'];
										  }
										  if($cell==''){
											  $resp = get_sport($sport,$day3);
											  $objWorkSheet->setCellValue($column.$rowCount,$resp);
											  $day3++;
											  $c3++;
										  }
										  
										
									  }
									  $day3rowslast[$rowCount] = $day3-1;
									  $day3LastArray = array_merge_values($day3LastArray,$day3rowslast);
									
                                }
									$objWorkSheet->setTitle("Daily Planner");
									
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
											//$objPHPExcel->getActiveSheet()->getStyle('H14:H18')->applyFromArray($coachessStyleArray);
											//$objPHPExcel->getActiveSheet()->getStyle('I14:I17')->applyFromArray($coachessStyleArray);
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
											//$objPHPExcel->getActiveSheet()->getStyle('B14:B15')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('B17:B18')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('B20:B21')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('C14:C15')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('G14:G16')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('G18')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('H14:H16')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('H18')->applyFromArray($coachessStyleArray_new);
											//$objPHPExcel->getActiveSheet()->getStyle('K14')->applyFromArray($coachessStyleArray_new);
											$objPHPExcel->getActiveSheet()->getStyle('L14')->applyFromArray($coachessStyleArray_new);
											$objWorkSheet->setCellValue('A'.$rowCount, $j);
													   
											if($i == 1){
											  $objWorkSheet->setCellValue('C'.$rowCount,$sportsDataObj->getSportDataByID(4,0));
											  if($sportsDataObj->getSportDataByID(4,0) != ''){
												//$objPHPExcel->getActiveSheet()->getStyle('C14:C18')->applyFromArray($coachessStyleArray_new);
											  }
											}else{
											  $objWorkSheet->setCellValue('C'.$rowCount,$sportsDataObj->getSportDataByID(4,0));
											  if($sportsDataObj->getSportDataByID(4,0) != ''){
												//$objPHPExcel->getActiveSheet()->getStyle('C14:C18')->applyFromArray($coachessStyleArray_new);
											  }
											}
											if($i == 1){
											  if($sportsDataObj->getSportDataByID(4,0) != ''){
												//$objPHPExcel->getActiveSheet()->getStyle('D14:D20')->applyFromArray($coachessStyleArray_new);
											  }
											}else{
											  $objWorkSheet->setCellValue('D'.$rowCount,'');
											  if($sportsDataObj->getSportDataByID(4,0) != ''){
												//$objPHPExcel->getActiveSheet()->getStyle('D14:D20')->applyFromArray($coachessStyleArray_new);
											  }
											}
											if( $i == 2){
												//$objWorkSheet->setCellValue('E'.$rowCount,$sportsDataObj->getSportDataByID(8,0));
											}
				  
											if($totalcoach > 3){
												$persontoLunch = ($totalcoach/2);
												$calcStudent = round($persontoLunch);
												$remianingStudent = $totalcoach - round($persontoLunch);
												$rendercount  = 5 + $calcStudent; 
												if($rowCount < $rendercount){
												  $objWorkSheet->setCellValue('G'.$rowCount,'Lunch');
													if($i == 1){
														//$objWorkSheet->setCellValue('L'.$rowCount,'T');
													}
												}else{
												  $objWorkSheet->setCellValue('H'.$rowCount,'Lunch');
													if($i == 1){
														//$objWorkSheet->setCellValue('M'.$rowCount,'T');
													}
												}
											}else{
												$objWorkSheet->setCellValue('H'.$rowCount,'Lunch');
												if($i == 1){
													// $objWorkSheet->setCellValue('M'.$rowCount,'T');
												} 
											}
											if($i != 1){
												$objWorkSheet->setCellValue('I'.$rowCount,'Games');
											}
											if($i == 1){
												$objWorkSheet->setCellValue('J'.$rowCount,'Games');
											}else{
												$objWorkSheet->setCellValue('J'.$rowCount,'Match Play');
											} 
											$objWorkSheet->setCellValue('K'.$rowCount,$sportsDataObj->getSportDataByID(1,0));
											$lastColumn = "K";
											$lastColumn++;
											if($i==2){
												$rowc1=5;
												$counter11 = 0;
												foreach($vpd1 as $vprow1){
													if($counter11 === $totalcoach){
													  break;
													}
													$cell1 = $objWorkSheet->getCell($vprow1.$rowCount);
													if($cell1!='L'){
														$objWorkSheet->setCellValue($vprow1.$rowc1,$sportsDataObj->getSportDataByID(8,0));
														$rowc1++;
														$counter11++;
													}   
												} 
											} 
											if($i==1){ 
												$rowc1=5;
												$counter1 = 0; 
												foreach($vpd1 as $vprow1){
													if($counter1 === $totalcoach){
													  break;
													}
													$cell1 = $objWorkSheet->getCell($vprow1.$rowCount);
													if($cell1!='L'){
														$objWorkSheet->setCellValue($vprow1.$rowc1,$sportsDataObj->getSportDataByID(5,0));
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
														$objWorkSheet->setCellValue($vprow2.$rowc2,$sportsDataObj->getSportDataByID(5,0));
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
														$objWorkSheet->setCellValue($vprow3.$rowc3,$sportsDataObj->getSportDataByID(5,0));
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
													  
													  $resp = get_sport($sport,$day1);
													  
													  $objWorkSheet->setCellValue($column.$rowCount,$resp);
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
													   $resp = get_sport($sport,$day2);
													  $objWorkSheet->setCellValue($column.$rowCount,$resp);
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
													$c3=2;
													$day3=$rows[$c3]['activity_sort_name'];
												  }
												  if($cell==''){
													  $resp = get_sport($sport,$day3);
													  $objWorkSheet->setCellValue($column.$rowCount,$resp);
													  $day3++;
													  $c3++;
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
											/*$objWorkSheet->setCellValue("H14","V&P = Video Record & PB");
											$objWorkSheet->setCellValue("H15","T = Talent");
											$objWorkSheet->setCellValue("H16","FT = Fittness Test");
											$objWorkSheet->setCellValue("H17","L = Lunch");
											$objWorkSheet->setCellValue("H18","SO = SignOut");
											$objWorkSheet->setCellValue("I14","P = Presentation");
											$objWorkSheet->setCellValue("I15","W = Welcome");
											$objWorkSheet->setCellValue("I16","WU = Warm up");
											$objWorkSheet->setCellValue("I17","D = Drinks");
											$coachessStyleArr = array(
											  'borders' => array(
												'allborders' => array(
												  'style' => PHPExcel_Style_Border::BORDER_THIN
												)
											  )
											);
											$objPHPExcel->getActiveSheet()->getStyle('H14:H18')->applyFromArray($coachessStyleArr);
											$objPHPExcel->getActiveSheet()->getStyle('I14:I17')->applyFromArray($coachessStyleArr);
											$objWorkSheet->setCellValue("A14","NOTE:");
											$objWorkSheet->setCellValue("B14","NO DESIGNATED DRINKS BREAKS, COACHES TO INCORPORATE DRINKS BREAKS INTO 50 MIN COACHING BLOCKS");*/
										   // $objWorkSheet->setCellValue("B14","ASC COS");
											//$objWorkSheet->setCellValue("B15",$cos);
											//$objWorkSheet->setCellValue("B17","First Aid");
										   // $objWorkSheet->setCellValue("B18",$fa);
										   // $objWorkSheet->setCellValue("B20","Video");
										   // $objWorkSheet->setCellValue("B21",$video);
										   // $objWorkSheet->setCellValue("C14","ASC Admin Office");
										   // $objWorkSheet->setCellValue("C15","1300-914-368");
											//$objWorkSheet->setCellValue("G14",$sport);
										   // $objWorkSheet->setCellValue("G15","Coaching Director");
										   // $objWorkSheet->setCellValue("G16",$cd);
										   // $objWorkSheet->setCellValue("G18","Talent");
										   // $objWorkSheet->setCellValue("G19",$talent);
										   // $objWorkSheet->setCellValue("K14","Notes and Important info:");			
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
					header('Content-Type: application/vnd.ms-excel');
									header('Content-Disposition: attachment;filename="'.$camp_name.'.xls"');
									header('Cache-Control: max-age=0');
									$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
									$objWriter->save('php://output');			
					
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