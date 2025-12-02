<?php
	ini_set('memory_limit', -1);
	error_reporting(E_ALL);
	ini_set("display_errors", true);
	ini_set('max_execution_time', 10800); // 3 Hour
	set_time_limit(0);
	require __DIR__."/libraries/dompdf/autoload.inc.php";
	use Dompdf\Dompdf;

	if(isset($_POST['hdnAction']))
	{
		include "include/common.php";
		$objTalents=new BROCHURE_TALENTS();

		// Get posted data
		$state=$_POST['lstState'];
		$sports=$_POST['lstSports'];
		$isPartner=0;
		if(isset($_POST['chkIsPartner']))
		{
			$isPartner=$_POST['chkIsPartner'];
		}
		$suburb=$_POST['txtSuburb'];
		$dates=$_POST['txtDates'];
		$season=$_POST['lstSeason'];
		$year=$_POST['lstYear'];
		$talent_1=$_POST['lstTalent1'];
		$talent_2=$_POST['lstTalent2'];
		$venueName=$_POST['txtVenueName'];
		$venueAddress=$_POST['taVenueAddress'];

		$templateDir=__DIR__."/brochure_templates/";
		$templateImagesDir=__DIR__."/brochure_templates/images/";
		$talentImagesDirAbsPath=__DIR__."/images/talents";
		$sports="";
		$template="";

		if(isset($_POST['lstSports']) && !empty($_POST['lstSports']))
		{
			$sports=trim($_POST['lstSports']);
			$template=strtolower($sports);
			//echo $template;
			if($template=="aflw")
			{
				$template="afl";
			}
			$template=str_replace(" ","_",$template);
		}

			if($isPartner==1)
			{
				$templateFile=$templateDir."partner_".$template.".html";
				//print_r($templateFile);
			}
			else
			{
				$templateFile=$templateDir.$template.".html";
				//print_r($templateFile);
			}
		$htmlsampleday = "";
		$htmltime = "";
		// echo $templateFile; die;
		if(file_exists($templateFile))
		{
			ob_start();
			 $html=file_get_contents($templateFile);
			
			
			
			$sample_day= $objTalents->GetSampleDay($suburb,$sports,$state);
			$sampledayarr = mysqli_fetch_assoc($sample_day);
			$htmlsampleday = implode(',', $sampledayarr);
			//$htmltime = $sampledayarr['time_data'];
			
					


			/**//*$htmlsampleday='<p style="text-align: justify; font-size: 11pt; line-height: 17px;>'.$htmlsampleday.'</p>';


			$htmlsampleday=str_replace("\n", '</p></li><li><p style="text-align: justify; font-size: 11pt; line-height: 17px;">', $htmlsampleday);
*/

			/*new*/
			$htmlsampleday='<li style="margin-left:15px;"><p style="text-align: justify; font-size: 11pt; line-height: 12px;margin-top:5px;margin-bottom:5px;">'.$htmlsampleday.'</p></li>';
			
			$htmlsampleday=str_replace("\n", '</p></li><li style="margin-left:15px;"><p style="text-align: justify; font-size: 11pt; line-height: 12px;margin-top:5px;margin-bottom:5px;">', $htmlsampleday);
				
						/*end of new */

			$html=str_replace("{sample_day}", $htmlsampleday, $html);
			

			$time_data= $objTalents->GetTime($suburb,$sports,$state);
			$timedataarr = mysqli_fetch_assoc($time_data);
			$htmltime = $timedataarr['time_data'];

			$htmltime='<p style="text-align: justify; font-size: 11pt; margin-bottom: 20px; line-height: 15px;">'.$htmltime.'</p>';

			$htmltime=str_replace("\n", '</p><p style="text-align: justify; font-size: 11pt; margin-bottom: 20px; line-height: 15px;">', $htmltime);

			$html=str_replace("{time}", $htmltime, $html);

				//05-03-2020 start
			 $camp_ability = $objTalents->GetAllData($suburb,$sports,$state);
            $arrability = mysqli_fetch_assoc($camp_ability);
            $htmlability = $arrability['Camps_Abilities'];
            $html_asc_overview = $arrability['ASC_Overview'];

            $html=str_replace("{camps_ability}", $htmlability, $html);
            $html=str_replace("{camps_asc_overview}", $html_asc_overview, $html);

				//05-03-2020 end

		



		

			// Check selected talents and decide layout (1 Column/2 Column)
			$talentLayout=1;
			$htmlPreviousTalents="";
			if($talent_1!="" && $talent_2!="")
			{
				$talentLayout=2;
				
				if($isPartner==1){
					$htmlPreviousTalents=file_get_contents($templateDir."/parts/previous_talent_2_col_partner.html");
				}
				else{
					$htmlPreviousTalents=file_get_contents($templateDir."/parts/previous_talent_2_col.html");
				}
				
				
			}
			else if($talent_1!="")
			{
				$talentLayout=1;
				if($isPartner==1){
					$htmlPreviousTalents=file_get_contents($templateDir."/parts/previous_talent_1_col_partner.html");
				}
				else{
					$htmlPreviousTalents=file_get_contents($templateDir."/parts/previous_talent_1_col.html");
				}
				
			}

			$html=str_replace("{previous_talents}", $htmlPreviousTalents, $html);
			
			if($talent_1!="")
			{
				$rsltTalent=$objTalents->getTalentById($talent_1);
				$rowTalent=$rsltTalent->fetch_assoc();

				// Add bullets to description
				$talentDescription="";
				if($talentLayout==1)
				{
					// For 1 column
					$talentDescription=$rowTalent['talent_description'];
				}
				else if($talentLayout==2)
				{
					// For 2 column
					$talentDescription=$rowTalent['talent_description_short'];
				}
				$talentDescription='<li><p style="line-height: 14px; font-family: Montserrat-Regular; font-size: 10.99pt;">'.$talentDescription.'</p>';

				$talentDescription=str_replace("\n", '</p></li>
					<li><p style="line-height: 14px; font-family: Montserrat-Regular; font-size: 10.99pt;">', $talentDescription);


				$html=str_replace("{previous_talent_1_name}", $rowTalent['talent_name'], $html);
				$html=str_replace("{previous_talent_1_image}", $talentImagesDirAbsPath."/".$rowTalent['talent_image'], $html);
				$html=str_replace("{previous_talent_1_history}", $talentDescription, $html);
			}
			if($talent_2!="")
			{
				$rsltTalent=$objTalents->getTalentById($talent_2);
				$rowTalent=$rsltTalent->fetch_assoc();

				// Add bullets to description
				$talentDescription="";
				if($talentLayout==1)
				{
					// For 1 column
					$talentDescription=$rowTalent['talent_description'];
				}
				else if($talentLayout==2)
				{
					// For 2 column
					$talentDescription=$rowTalent['talent_description_short'];
				}
				$talentDescription='<li><p style="line-height: 14px; font-family: Montserrat-Regular; font-size: 10.99pt;">'.$talentDescription.'</p></li>';

				$talentDescription=str_replace("\n", '</p></li>
					<li><p style="line-height: 14px; font-family: Montserrat-Regular; font-size: 10.99pt;">', $talentDescription);

				$html=str_replace("{previous_talent_2_name}", $rowTalent['talent_name'], $html);
				$html=str_replace("{previous_talent_2_image}", $talentImagesDirAbsPath."/".$rowTalent['talent_image'], $html);
				$html=str_replace("{previous_talent_2_history}", $talentDescription, $html);
			}

			$formattedDates=getDatesHtml($dates);
			$formattedDates=str_replace("<sup>", '<sup style="font-size: 12pt;">', $formattedDates);

			// Replace all form data
			$html=str_replace("{camp_state}", $state, $html);
			$html=str_replace("{camp_sports}", $sports, $html);
			$html=str_replace("{camp_suburb}", $suburb, $html);
			$html=str_replace("{camp_dates}", $formattedDates, $html);
			$html=str_replace("{camp_season}", $season, $html);
			$html=str_replace("{camp_year}", $year, $html);
			$html=str_replace("{camp_venue_name}", $venueName, $html);
			$html=str_replace("{camp_venue_address}", nl2br($venueAddress), $html);


			$html=str_replace("{brochure_images_dir}", "brochure_templates/images", $html);

			 $html=str_replace("{banner_image_path}", "brochure_templates/images", $html);
			
			/*$html=str_replace("{image_bg}", "brochure_templates/images", $html);*/

			/*$html=str_replace("{campus_banner}", "brochure_templates/images/campus-banner.jpg", $html);*/

			/*$html=str_replace("{about_banner}", "brochure_templates/images/tennis-about-banner.png", $html);*/

			/*$html=str_replace("{why_asc}", "brochure_templates/images/why-asc.png", $html);*/

			/*$html=str_replace("{footer_logo}", "brochure_templates/images/footer-logo.png", $html);*/

			/*$html=str_replace("{envalop}", "brochure_templates/images/envalop.png", $html);*/

			/*$html=str_replace("{instagram}", "brochure_templates/images/instagram.png", $html);*/

			/*$html=str_replace("{facebook}", "brochure_templates/images/facebook.png", $html);*/

			/*$html=str_replace("{twitter}", "brochure_templates/images/twitter.png", $html);*/

			

			$dompdf = new Dompdf(array('enable_font_subsetting' => true, 'enable_remote'=>true));
			$dompdf->set_option("isPhpEnabled", true);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portable');
			$dompdf->set_option('isHtml5ParserEnabled', true);
			// Render the HTML as PDF
			$dompdf->render();
			$pdf_string =  $dompdf->output();

			header("Content-Type:application/pdf");
			if($_POST['hdnAction']=="download")
			{
				$dates=str_replace(",", " ", $dates);
				$fileName=$state." ".$sports." ".$suburb." (".$dates.").pdf";
				header("Content-Disposition: attachment; filename=$fileName;");
			}
			echo $pdf_string;
			ob_end_flush();
		}
		else
		{
			echo "<p>Template Not Found.</p>";
		}
?>

<?php
	}
	else
	{
?>
		<p>Preview will be shown here.</p>
<?php
	}
function getDatesHtml($dates)
{
	$dates=preg_replace("/(\d)th/", "$1<sup>th</sup>", $dates);
	$dates=preg_replace("/(\d)st/", "$1<sup>st</sup>", $dates);
	$dates=preg_replace("/(\d)rd/", "$1<sup>rd</sup>", $dates);
	return $dates;
}
?>