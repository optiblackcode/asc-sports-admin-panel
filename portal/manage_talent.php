<?php
include "include/common.php";
$objTalents=new BROCHURE_TALENTS();

$imagesDirAbsPath=__DIR__."/images/talents";
$imagesDirUrl="images/talents";

$arrErrors=[
  'name'=>'',
  'image'=>'',
  'description'=>'',
];


$arrFormData=[
	'txtName'=>'',
	'flImage'=>'',
	'taDescription'=>'',
  'taDescriptionShort'=>'',
	'hdnId'=>''
];
if(isset($_GET['id']) && !empty($_GET['id']))
{
	$hdnId=trim($_GET['id']);
	$rsltTalent=$objTalents->getTalentById($hdnId);
	if($rsltTalent)
	{
		if($rsltTalent->num_rows==1)
		{
			$rowTalent=$rsltTalent->fetch_assoc();
			$arrFormData['txtName']=$rowTalent['talent_name'];
      $arrFormData['lstSports']=explode(",",$rowTalent['talent_sports']);
			$arrFormData['flImage']=$rowTalent['talent_image'];
			$arrFormData['taDescription']=$rowTalent['talent_description'];
      $arrFormData['taDescriptionShort']=$rowTalent['talent_description_short'];
			$arrFormData['hdnId']=$rowTalent['rec_id'];
		}
	}
}
if(isset($_POST['submit']))
{
  $blError=false;
  $talentName="";
  $talentSports="";
  $talentDescription="";
  $talentDescriptionShort="";
  $hdnImage="";
  $flImage="";
  $hdnId="";

  if(trim($_POST['hdnId'])!="")
  {
    $hdnId=trim($_POST['hdnId']);
  }

  if(trim($_POST['txtName'])!="")
  {
    $talentName=trim($_POST['txtName']);
  }
  else
  {
    $blError=true;
    $arrErrors['name']="Please enter name.";
  }

  if($_POST['lstSports'] && count($_POST['lstSports'])>0)
  {
    $talentSports=implode(",", $_POST['lstSports']);
  }
  else
  {
    $blError=true;
    $arrErrors['sports']="Please enter sports.";  
  }

  if(trim($_POST['taDescription'])!="")
  {
    $talentDescription=trim($_POST['taDescription']);
  }
  else
  {
    $blError=true;
    $arrErrors['description']="Please enter description.";
  }

  if(trim($_POST['taDescriptionShort'])!="")
  {
    $talentDescriptionShort=trim($_POST['taDescriptionShort']);
  }
  else
  {
    $blError=true;
    $arrErrors['description']="Please enter description.";
  }

  if(trim($_POST['hdnImage'])!="")
  {
    $hdnImage=trim($_POST['hdnImage']);
  }

  if($hdnImage=="")
  {

    if(isset($_FILES['flImage']))
    {

      if($_FILES['flImage']['error']==0)
      {
        $uplodedName=$_FILES['flImage']['name'];
        $arrPath=pathinfo($uplodedName);
        $uploadedExt=$arrPath['extension'];

        // create unique image name
        $imageName=date("YmdHis")."_".rand(1000,9999).".".$uploadedExt;
        if(move_uploaded_file($_FILES['flImage']['tmp_name'], $imagesDirAbsPath."/".$imageName))
        {
          $flImage=$imageName;
        }
        else
        {
          $blError=true;
          $arrErrors['image']="Failed to upload image.";
        }
      }
      else if($_FILES['flImage']['error']==4)
      {
        $blError=true;
        $arrErrors['image']="Please select image.";
      }
      else
      {
        $blError=true;
        $arrErrors['image']="Failed to upload image.";
      }
    }
    else
    {
      $blError=true;
      $arrErrors['image']="Please select image.";
    }

  }

  if(!$blError)
  {
    // Insert/Update talent
    $arrTalent=[];
    $arrTalent['talent_name']=$talentName;
    $arrTalent['talent_sports']=$talentSports;
    $arrTalent['talent_description']=$talentDescription;
    $arrTalent['talent_description_short']=$talentDescriptionShort;
    if($flImage!="")
    {
      $arrTalent['talent_image']=$flImage;
    }

    if($hdnId=="")
    {
      // Insert case
      if($objTalents->insertTalent($arrTalent))
      {
        header("Location:talents.php?msg=ins");
        die();
      }
      else
      {
      	header("Location:talents.php?msg=insF");
        die();	
      }
    }
    else
    {
    	if(isset($arrTalent['talent_image']) && !empty($arrTalent['talent_image']))
		{
	    	$rsltTalent=$objTalents->getTalentById($hdnId);
			if($rsltTalent)
			{
				if($rsltTalent->num_rows==1)
				{
					$rowTalent=$rsltTalent->fetch_assoc();
					$imageToDelete=$rowTalent['talent_image'];
					if(file_exists($imagesDirAbsPath."/".$imageToDelete))
					{
						unlink($imagesDirAbsPath."/".$imageToDelete);
					}
				}
			}
		}
      // Update case
      if($objTalents->updateTalent($hdnId,$arrTalent))
      {
        header("Location:talents.php?msg=upd");
        die();
      }
      else
      {
      	header("Location:talents.php?msg=updF");
        die();
      }
    }
  }

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
                <h3>Brochure</h3>
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
                    <br>
                    <form id="demo-form2" class="form-horizontal form-label-left" method="POST" enctype="multipart/form-data" onsubmit="return fnValidation();">
                      <?php 
                        $id="hdnId";
                      ?>
                      <input type="hidden" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo getValue($id);?>" readonly>
                      	<?php 
  							$id="flImage";
  							$imageName=getValue($id);
  							if($imageName=="")
  							{
  								$imageName="no-image.jpg";
  							}
  					  	?>
                      <div class="form-group">
                        <div class="col-md-3 col-sm-3 col-xs-12"></div>
                        <div class="col-md-6 col-sm-6 col-xs-12" style="text-align: center;">
                        	<img src="images/talents/<?php echo $imageName;?>" class="talent-image" id="imgPreviewImage" />
                          <br/><br/>
                          <input type="file" name="<?php echo $id;?>" id="<?php echo $id;?>" style="text-align: center; display: inline-block;" class="talent-image">
                          <input type="hidden" name="hdnImage" id="hdnImage" value="<?php echo getValue('flImage');?>">
                          <br/>
                          <span class="error"><?php echo $arrErrors['image'];?></span>
                        </div>
                      </div>
                      <?php 
          							$id="txtName";
          						?>
	                    <div class="form-group">
	                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="<?php echo $id;?>">Name : 
	                        </label>
	                        <div class="col-md-6 col-sm-6 col-xs-12 control-label" style="text-align: left;">
	                        	<b></b>
	                         	<input type="text" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo getValue($id);?>" class="form-control">
	                          <span class="error"><?php echo $arrErrors['name'];?></span>
	                        </div>
	                    </div>
                      <?php 
                        $id="lstSports";
                        $arrSports=getSports();
                        unset($arrSports['AFLW']);

                        $arrSelectedSports=getValue($id);
                      ?>
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="<?php echo $id;?>">Sports : 
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12 control-label" style="text-align: left;">
                            <b></b>
                            <select name="<?php echo $id; ?>[]" id="<?php echo $id; ?>" class="form-control select2-js" multiple>
                              <?php
                                foreach ($arrSports as $key => $value) {
                              ?>
                                <option value="<?php echo $key;?>" <?php if(in_array($key, $arrSelectedSports)){ echo "selected"; }?>><?php echo $value;?></option>
                              <?php 
                                }
                              ?>
                            </select>
                            <span class="error"><?php echo $arrErrors['sports'];?></span>
                          </div>
                      </div>
                      <?php 
            							$id='taDescription';
            					?>
	                    <div class="form-group">
	                        <label for="<?php echo $id;?>" class="control-label col-md-3 col-sm-3 col-xs-12">Long Description: </label>
	                        <div class="col-md-6 col-sm-6 col-xs-12">
	                          <textarea  name="<?php echo $id; ?>" id="<?php echo $id; ?>" maxlength="650" rows="8" class="form-control"><?php echo getValue($id);?></textarea>
	                          <span class="error"><?php echo $arrErrors['description'];?></span>

                            <dt id='stat'><label>&#40;<span>650</span> characters left&#41;</label></dt>
	                        </div>
	                    </div>
                      <?php 
                          $id='taDescriptionShort';
                      ?>
                      <div class="form-group">
                          <label for="<?php echo $id;?>" class="control-label col-md-3 col-sm-3 col-xs-12">Short Description: </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea  name="<?php echo $id; ?>" id="<?php echo $id; ?>" rows="8" maxlength="260" class="form-control"><?php echo getValue($id);?></textarea>
                            <span class="error"><?php echo $arrErrors['description_short'];?></span>

                            <dt id='ssss'><label>&#40;<span>260</span> characters left&#41;</label></dt>
                          </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        	<a href="talents.php">
                          <button class="btn btn-primary" type="button">Cancel</button></a>
                          <button type="submit" class="btn btn-success" name="submit">Submit</button>
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
		<?php 
			// Include common footer for all pages
			include "include/common_footer.php";
		?>
	</body>
</html>
<?php
function getValue($id)
{
	global $arrFormData;
	$value="";
	if(isset($_POST[$id]))
	{
		$value=$_POST[$id];
	}
	else if(isset($arrFormData[$id]))
	{
		$value=$arrFormData[$id];
	}
	return $value;
}
?>
<script type="text/javascript">
  function readURL(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        $('#imgPreviewImage').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
    else
    {
       $('#imgPreviewImage').attr('src','images/talents/no-image.jpg');
    }
  }

  $("#flImage").change(function() {
    readURL(this);
    $("#hdnImage").val('');
  });

  function fnValidation(){

    $(".form-group .error").html("");

    var name=$("#txtName").val();
    var description=$("#taDescription").val();
    var sports=$("#lstSports").val();
    var hdnImage=$("#hdnImage").val();
    var flImage=$("#flImage").val();

    var blError=false;
    if(name.trim()=="")
    {
      var msg="Please enter name.";
      $("#txtName").parents(".form-group").find(".error").html(msg);
      blError=true;
    }
    if(sports==null)
    {
      var msg="Please enter sports.";
      $("#lstSports").parents(".form-group").find(".error").html(msg);
      blError=true; 
    }
    if(description.trim()=="")
    {
      var msg="Please enter description.";
      $("#taDescription").parents(".form-group").find(".error").html(msg);
      blError=true;
    }
    if(flImage.trim()=="" && hdnImage.trim()=="")
    {
      var msg="Please select image.";
      $("#flImage").parents(".form-group").find(".error").html(msg);
      blError=true;
    }

    if(blError)
    {
      return false;
    }
  }
</script>
<script type="text/javascript">
  function countCharr(val) {
    var len = val.value.length;

    if (len >= 650) {
        val.value = val.value.substring(0, 650);
        $('#stat span').text(0);
    } else {
        $('#stat span').text(650 - len);
    }
}
countCharr($('#taDescription').get(0));
$('#taDescription').keyup(function() {
    countCharr(this);
});
</script>


<script type="text/javascript">
  function countChar(val) {
    var cou = val.value.length;

    if (cou >= 260) {
        val.value = val.value.substring(0, 260);
        $('#ssss span').text(0);
    } else {
        $('#ssss span').text(260 - cou);
    }
}
countChar($('#taDescriptionShort').get(0));
$('#taDescriptionShort').keyup(function() {
    countChar(this);
});
</script>

