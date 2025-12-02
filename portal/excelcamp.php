<?php
 // header('Content-Type: application/vnd.ms-excel');
 //  header('Content-Disposition: attachment;filename="camp.xls"');
 //  header('Cache-Control: max-age=0');
 //  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
 //  $objWriter->save('php://output');

if(isset($_GET["filename"]))
{
 if(file_exists($_GET["filename"]))
 {
  header("Content-Type: application/octet-stream");
  header("Content-Disposition: attachment; filename=" .  $_GET["filename"]);
  readfile($_GET["filename"]);
  unlink($_GET["filename"]);
 }
 else
 {
  echo 'No File Found';
 }
}