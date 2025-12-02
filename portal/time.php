<?php
include "include/common.php";
$objTimes=new BROCHURE_TIME();
$objCamps=new ZOHO_CAMPS();
$arrStates=getStates();
$arrSports=getSports();

$table = "is_partner_sample_data";
$id = $_GET['id'];


$arrSeasons=[
  'Autumn',
  'Winter',
  'Spring',
  'Summer'
];

 
   

  if(isset($_POST['submit'])) 
  { 
    // Insert/Update talent
   /*$state= $_POST['lstState'];
   $sports = $_POST['lstSports'];
   $suburb = $_POST['txtSuburb'];
   $sample_day = $_POST['Sample_day'];
   $time_data = $_POST['timeDescription'];
   */

     if($id)
     {
          $arrData=[];
          $arrData['Suburb'] = $_POST['txtSuburb'];
          $arrData['Sports'] = $_POST['lstSports'];
          $arrData['State'] = $_POST['lstState'];
          $arrData['Sample_day']=$_POST['Sample_day'];
          $arrData['time_data']=$_POST['timeDescription'];
          $arrData['Camps_Abilities'] = $_POST['CampsforallAbilities'];
          $arrData['ASC_Overview'] = $_POST['ASCCampsOverview'];
          $whereClause="where id='$id'";

          $data = $objTimes->common_update($table,$arrData,$whereClause);
          header('Location: http://31.220.55.121/portal/time_view.php');
          exit();
     }
     else
     {
          $arrData=[];
          $arrData['Suburb'] = $_POST['txtSuburb'];
          $arrData['Sports'] = $_POST['lstSports'];
          $arrData['State'] = $_POST['lstState'];
          $arrData['Sample_day']=$_POST['Sample_day'];
          $arrData['time_data']=$_POST['timeDescription'];
          $arrData['Camps_Abilities'] = $_POST['CampsforallAbilities'];
          $arrData['ASC_Overview'] = $_POST['ASCCampsOverview'];
          $data = $objTimes->common_insert($table,$arrData);
          header('Location: http://31.220.55.121/portal/time_view.php');
          exit();
     }
  }
$timedata = $objTimes->getTalentById($id);
$row = $timedata -> fetch_array(MYSQLI_ASSOC);
  
    
   
        
    /*else
    {
      
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
    }*/
  

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
                <h3>Timetable</h3>
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
                      <div class="col-md-6">
                        <form id="frmPdf" class="form-horizontal form-label-left" method="POST" enctype="multipart/form-data" action="">
                          <input type="hidden" value="preview" id="hdnAction" name="hdnAction" />
                          <!-- 10-10-2020 start-->  
                          
                           <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">State : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <?php 
                                  $id="lstState";
                                ?>
                                <select class="form-control" name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                                  <option value="">--Select State--</option>
                                  <?php
                                    foreach ($arrStates as $state) 
                                    {
                                  ?>
                                      <option value="<?php echo $state;?>" <?php if($row['State']==$state) echo 'selected="selected"'; ?>><?php echo $state;?></option>
                                  <?PHP
                                    }
                                  ?>
                                </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>


                          <?php 
                            $id="lstSports";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sports">Sports : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <select class="form-control" name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                                  <option value="">--Select Sports--</option>
                                  <?php
                                    foreach ($arrSports as $key => $value) {
                                  ?>
                                      <option value="<?php echo $key;?>"  <?php if($row['Sports']==$key) echo 'selected="selected"'; ?>><?php echo $value;?></option>
                                  <?php
                                    }
                                  ?>
                                </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>


                          <?php 
                            $id="txtSuburb";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Suburb : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <input type="text" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php if(getValue($id)) {echo getValue($id);}else{echo $row['Suburb'];}?>" class="form-control">
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>
                           <div class="form-group">
                              <label for="<?php echo $id;?>" class="control-label col-md-3 col-sm-3 col-xs-12">Sample Day:</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea  name="Sample_day" id="Sample_day" rows="10" class="form-control"><?php echo $row['Sample_day']; ?></textarea>
                                <span class="error"><?php echo $arrErrors['Sample_day'];?></span>
                              </div>
                          </div>
                           <?php 
                            $id='timeDescription';
                            ?>
                          <div class="form-group">
                              <label for="<?php echo $id;?>" class="control-label col-md-3 col-sm-3 col-xs-12">Time Description:</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea  name="<?php echo $id;?>" id="<?php echo $id;?>" rows="8" maxlength="260" class="form-control"><?php echo $row['time_data']; ?></textarea>
                                <span class="error"><?php echo $arrErrors['description_short'];?></span>

                                <dt id='ssss'><label>&#40;<span>260</span> characters left&#41;</label></dt>
                              </div>
                          </div>
                          <!-- 10-10-2020 end -->   
                          <!-- 03-02-2020 start-->
                           <?php 
                            $id='CampsforallAbilities';
                            ?>
                          <div class="form-group">
                              <label for="<?php echo $id;?>" class="control-label col-md-3 col-sm-3 col-xs-12">Camps for all Abilities:</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea  name="<?php echo $id;?>" id="<?php echo $id;?>" rows="8" maxlength="500" class="form-control"><?php echo $row['Camps_Abilities']; ?></textarea>
                                <span class="error"><?php echo $arrErrors['description_short'];?></span>

                                <dt id='Abilitiesssss'><label>&#40;<span>500</span> characters left&#41;</label></dt>
                              </div>
                          </div>  

                          <?php 
                            $id='ASCCampsOverview';
                            ?>
                          <div class="form-group">
                              <label for="<?php echo $id;?>" class="control-label col-md-3 col-sm-3 col-xs-12">ASC Camps Overview:</label>
                              <div class="col-md-6 col-sm-6 col-xs-12">
                                <textarea  name="<?php echo $id;?>" id="<?php echo $id;?>" rows="8" maxlength="500" class="form-control"><?php echo $row['ASC_Overview']; ?></textarea>
                                <span class="error"><?php echo $arrErrors['description_short'];?></span>

                                <dt id='Overviewsssss'><label>&#40;<span>500</span> characters left&#41;</label></dt>
                              </div>
                          </div>  
                          <!-- 03-02-2020 End-->
                           
                          <div class="ln_solid"></div>
                          <div class="form-group">
                            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                              <button class="btn btn-primary" type="submit" name="submit" onClick="return fnValidation('preview');">Submit</button></a>
                              <button type="cancel" class="btn btn-success" name="cancel" onClick="javascript:window.location='http://31.220.55.121/portal/datewise_events.php';return false;">Cancel</button>
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
		</div>
		<?php 
			// Include common footer for all pages
			include "include/common_footer.php";
		?>
    <script type="text/javascript">
    
     

      function fnValidation(action){
        $("#hdnAction").val(action);
        $(".form-group .error").html("");

        var state=$("#lstState").val();
        var sports=$("#lstSports").val();
        var suburb=$("#txtSuburb").val();
        var timeDescription=$("#timeDescription").val();
        var sample_day = $("#Sample_day").val();
        var CampsforallAbilities = $("#CampsforallAbilities").val();
        var blError=false;
        if(state.trim()=="")
        {
          var msg="Please enter state.";
          $("#lstState").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(sports.trim()=="")
        {
          var msg="Please enter sports.";
          $("#lstSports").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(suburb.trim()=="")
        {
          var msg="Please enter suburb.";
          $("#txtSuburb").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(timeDescription.trim()=="")
        {
          var msg="Please enter time description.";
          $("#timeDescription").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(CampsforallAbilities.trim()=="")
        {
          var msg="Please enter time description.";
          $("#CampsforallAbilities").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(blError)
        {
          return false;
        }
        showIframeLoader();
        return true;
      }

      $(document).ready(function(){
        // Get brochure details on camp select
        $("#lstCamp").change(function(){
          if($("#lstCamp").val()!="" || $("#lstCamp").val()!=null)
          {
            var campId=$("#lstCamp").val();
            $.ajax({
              url: "ajax_get_camp_details.php",
              method: "GET",
              data: { camp_id : campId },
              success:function(data){
                var objResponse=JSON.parse(data);
                console.log(objResponse);
                if(objResponse.status==1)
                {
                  objData=objResponse.data;
                  $("#lstState").val(objData.state);
                  $("#lstSports").val(objData.sports);
                  $("#lstSports").trigger("change");

                  if(objData.is_partner=="1")
                  {
                    $("#chkIsPartner").prop("checked",true);
                  }
                  else
                  {
                    $("#chkIsPartner").prop("checked",false);
                  }
                  $("#txtSuburb").val(objData.suburb);
                  //$("#txtDates").val(objData.dates);
                  //$("#txtVenueName").val(objData.venue_name);
                  //$("#taVenueAddress").val(objData.venue_address);
                }
              }
            });
          }
        });

        // Get talents on change of sports
        $("#lstSports").change(function(){
          if($("#lstSports").val()!="" || $("#lstSports").val()!=null)
          {
            var sports=$("#lstSports").val();
            $.ajax({
              url: "ajax_get_talent_from_sports.php",
              method: "GET",
              data: { sports : sports },
              success:function(data){
                var objResponse=JSON.parse(data);
                console.log(objResponse);
                if(objResponse.status==1)
                {
                  objData=objResponse.data;

                  var talents1='<option value="">--Select Talent 1--</option>'+objData.talents;
                  $("#lstTalent1").html(talents1);
                }
              }
            });
          }
        });
      });
    </script>
    <!-- 10-02-2020 start-->
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
        countChar($('#timeDescription').get(0));
        $('#timeDescription').keyup(function() {
            countChar(this);
        });

        function char_cnt_Abilities(val) {
            var cou = val.value.length;

            if (cou >= 500) {
                val.value = val.value.substring(0, 500);
                $('#Abilitiesssss span').text(0);
            } else {
                $('#Abilitiesssss span').text(500 - cou);
            }
        }

        char_cnt_Abilities($('#CampsforallAbilities').get(0));
        $('#CampsforallAbilities').keyup(function() {
            char_cnt_Abilities(this);
        });


         function char_cnt_overview(val) {
            var cou = val.value.length;

            if (cou >= 500) {
                val.value = val.value.substring(0, 500);
                $('#Overviewsssss span').text(0);
            } else {
                $('#Overviewsssss span').text(500 - cou);
            }
        }

        char_cnt_overview($('#ASCCampsOverview').get(0));
        $('#ASCCampsOverview').keyup(function() {
            char_cnt_overview(this);
        });
    </script>
    <!-- 10-02-2020 end -->
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
function findSeasonYearFromDate($date)
  {

    $purchaseYear=date("Y",strtotime($date));
    $purchaseMonth=date("m",strtotime($date));
    if($purchaseMonth <= 3 && $purchaseMonth >= 1)
    {
      $purchaseSeason = "Autumn";
    }
    else if($purchaseMonth <= 6 && $purchaseMonth >= 4)
    {
      $purchaseSeason = "Winter";
    }
    else if($purchaseMonth <= 9 && $purchaseMonth >= 7)
    {
      $purchaseSeason = "Spring";
    }
    else
    {
      $purchaseSeason = "Summer";
      if($purchaseMonth<2)
      {
        $purchaseYear--;
      }
    }

    $arrSeasonYear=[
      'season'=>$purchaseSeason,
      'year'=>$purchaseYear
    ];

    return $arrSeasonYear;
  }

  if($purchaseMonth <= 3 && $purchaseMonth >= 1)
    {
      $purchaseSeason = "Autumn";
    }
    else if($purchaseMonth <= 6 && $purchaseMonth >= 4)
    {
      $purchaseSeason = "Winter";
    }
    else if($purchaseMonth <= 9 && $purchaseMonth >= 7)
    {
      $purchaseSeason = "Spring";
    }

  /*  $purchaseYear=date("Y",strtotime($date));
    $purchaseMonth=date("m",strtotime($date));
    if($purchaseMonth <= 4 && $purchaseMonth >= 2)
    {
      $purchaseSeason = "Autumn";
    }
    else if($purchaseMonth <= 7 && $purchaseMonth >= 5)
    {
      $purchaseSeason = "Winter";
    }
    else if($purchaseMonth <= 10 && $purchaseMonth >= 8)
    {
      $purchaseSeason = "Spring";
    }
    else
    {
      $purchaseSeason = "Summer";
      if($purchaseMonth<3)
      {
        $purchaseYear--;
      }
    }*/
?>