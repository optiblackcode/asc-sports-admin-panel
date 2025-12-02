<?php
ini_set('max_execution_time', 10800); // 3 Hour
ini_set("memory_limit", "-1");
set_time_limit(0);


require_once 'libraries/Classes/PHPExcel.php';
require_once 'libraries/Classes/PHPExcel/IOFactory.php';

require_once __DIR__."/../zoho-asc-cron/curl_zoho/class/zoho_methods.class.php";

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
			$campname[] = $value['Product_Name'] .'-'. $value['id'];
            //echo "<pre>";
            //print_r($campname);
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

$objPHPExcel = new PHPExcel();

$sheet = $objPHPExcel->getActiveSheet();

$excel_mul = array('3108913000057991153','3108913000059007065');


$i=1;
while($i <= 3)
{


	try
	{
		if($objZoho->checkTokens())
		{

            foreach ($excel_mul as $k => $val) 
            {
                
                //echo "<pre>";
                //print_r($val);
              
			$Camprec=$objZoho->getRelatedRecords("Camps",$val,'Hired_Coaches16');

                                          
                                        
                                        
                                        $rowCount = 2;

                                        $objWorkSheet = $objPHPExcel->createSheet($i);
                                        foreach ($Camprec['data'] as $hk => $hiredcoach) 
                                        { 

                                             

                                        	$objWorkSheet->setCellValue('A1', "COACH")
                                        	->setCellValue('B1', "9:00 to 9:10")
                                        	->setCellValue('C1', "9:15 to 9:30")
                                        	->setCellValue('D1', "9:30 to 10:00")
                                        	->setCellValue('E1', "10:00 to 10:30")
                                        	->setCellValue('A'.$rowCount,$hiredcoach['Hired_Coaches']['name'])
                                        	->setCellValue('B'.$rowCount,'1')
                                        	->setCellValue('C'.$rowCount,'1')
                                        	->setCellValue('D'.$rowCount,'1')
                                        	->setCellValue('E'.$rowCount,'1');

										      // Rename sheet
                                        	$objWorkSheet->setTitle("day_".$i);

                                        	$rowCount++;


                                        }

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
                                $i++;
                            }


                            header('Content-Type: application/vnd.ms-excel');
                            header('Content-Disposition: attachment;filename="camp.xls"');
                            header('Cache-Control: max-age=0');
                            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                            $objWriter->save('php://output');

                            ?>