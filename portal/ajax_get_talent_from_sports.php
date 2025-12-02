<?php
include "include/common.php";
$objTalents=new BROCHURE_TALENTS();

$status=0;
$msg="";
$arrData=[];
if(isset($_GET['sports']) && !empty($_GET['sports']))
{
	$arrTalents=[];
	$sports=$_GET['sports'];
	$rsltTalents=$objTalents->getTalentBySports($sports);
	if($rsltTalents)
	{
		while($rowTalents=$rsltTalents->fetch_assoc())
		{
			$arrTalents[$rowTalents['rec_id']]=$rowTalents['talent_name'];
		}

		$strTalentOptions="";
		foreach ($arrTalents as $key => $value) {
			$strTalentOptions.='<option value="'.$key.'">'.$value.'</option>';
		}

		$arrData['talents']=$strTalentOptions;
		$status=1;
	}
}

$arrResponse=[];
$arrResponse['status']=$status;
$arrResponse['msg']=$msg;
$arrResponse['data']=$arrData;
echo json_encode($arrResponse);
?>