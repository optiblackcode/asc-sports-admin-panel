<?php
ini_set('max_execution_time', 10800); // 3 Hour
ini_set("memory_limit", "-1");
set_time_limit(0);
include "include/common.php";
$objTalents=new BROCHURE_TALENTS();
$objCamps=new ZOHO_CAMPS();
$arrStates=getStates();
$arrSports=getSports();
$arrSeasons=[
  'Autumn',
  'Winter',
  'Spring',
  'Summer'
];

// Get current season and year
$arrSeasonYear=findSeasonYearFromDate(date("Y-m-d"));



 echo $season=$arrSeasonYear['season'];
 echo $year=$arrSeasonYear['year'];

//$year = '2021';
//$season = 'Winter';

$arrAllTalents=[];
$rsltAllTalents=$objTalents->getAllTalents();

while($rowAllTalents=$rsltAllTalents->fetch_assoc())
{
  $arrAllTalents[]=$rowAllTalents;
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
                    <div class="row">
                      <div class="col-md-6">
                        <form id="frmPdf" class="form-horizontal form-label-left" method="POST" enctype="multipart/form-data" action="brochure_preview.php" target="iframePreview" >
                          <input type="hidden" value="preview" id="hdnAction" name="hdnAction" />
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Camp : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <?php 
                                  $id="lstCamp";

                                  // Get current camps
                                  $arrCamps=[];
                                  $rsltCamps=$objCamps->getCampsBySeasonYear($season,$year);
                                  if($rsltCamps)
                                  {
                                    while ($rowCamp=$rsltCamps->fetch_assoc()) 
                                    {
                                      $arrCamps[$rowCamp['rec_id']]=$rowCamp['camp_name'];
                                    }
                                  }

                                  
                                ?>
                                <select class="form-control select2-js" name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                                  <option value="">--Select Camp--</option>
                                  <?php

                                    foreach ($arrCamps as $campId=>$campName) 
                                    {
                                  ?>
                                      <option value="<?php echo $campId;?>"><?php echo $campName;?></option>
                                  <?PHP
                                    }
                                  ?>
                                </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>
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
                                      <option value="<?php echo $state;?>"><?php echo $state;?></option>
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
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Sports : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <select class="form-control" name="<?php echo $id; ?>" id="<?php echo $id; ?>">
                                  <option value="">--Select Sports--</option>
                                  <?php
                                    foreach ($arrSports as $key => $value) {
                                  ?>
                                      <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                  <?php
                                    }
                                  ?>
                                </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>

                          <?php 
                            $id="chkIsPartner";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Is Partner : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <input type="checkbox" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="1">
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
                                <input type="text" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo getValue($id);?>" class="form-control">
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>

                          <?php 
                            $id="txtDates";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Dates : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <input type="text" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo getValue($id);?>" class="form-control">
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>

                          <?php 
                            $id="lstSeason";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Season : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <select name="<?php echo $id; ?>" id="<?php echo $id; ?>" class="form-control">
                                  <option value="">--Select Season--</option>
                                  <?php
                                    foreach ($arrSeasons as $key => $value) {
                                  ?>
                                      <option value="<?php echo $value;?>" <?php if($season==$value){ echo "selected";} ?>><?php echo $value;?></option>
                                  <?php
                                    }
                                  ?>
                                </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>

                          <?php 
                            $id="lstYear";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Year : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                 <select name="<?php echo $id; ?>" id="<?php echo $id; ?>" class="form-control">
                                    <option value="">--Select Year--</option>
                                    <?php
                                     //$dat =  date('Y', strtotime('+1 year'));
                                     // $k = 0;
                                      $i=2016;
                                      for($i;$i<2050;$i++)
                                      {
                                        $k = $i + 1;
                                       $k =  substr($k,2);
                                    ?>
                                        <option value="<?php echo $i?>" <?php if($year==$i){ echo "selected"; }?>><?php echo $i?></option>
                                        <!-- for year like 2020-21 -->
                                       <!--  <option value="<?php echo $i."-".$k;?>" <?php if($year==$i){ echo "selected"; }?>><?php echo $i."-".$k;?></option> -->
                                    <?php
                                      }
                                    ?>
                                 </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>

                          <?php 
                            $id="lstTalent1";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Talent 1 : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                 <select name="<?php echo $id; ?>" id="<?php echo $id; ?>" class="form-control select2-js">
                                    <option value="">--Select Talent 1--</option>
                                    <?php
                                      foreach ($arrAllTalents as $key => $arrTalent)
                                      {
                                    ?>
                                        <option value="<?php echo $arrTalent['rec_id'];?>"><?php echo $arrTalent['talent_name'];?></option>
                                    <?php
                                      }
                                    ?>
                                 </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>

                          <?php 
                            $id="lstTalent2";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Talent 2 : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                 <select name="<?php echo $id; ?>" id="<?php echo $id; ?>" class="form-control select2-js">
                                    <option value="">--Select Talent 2--</option>
                                    <?php
                                      foreach ($arrAllTalents as $key => $arrTalent)
                                      {
                                    ?>
                                        <option value="<?php echo $arrTalent['rec_id'];?>"><?php echo $arrTalent['talent_name'];?></option>
                                    <?php
                                      }
                                    ?>
                                 </select>
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>

                          <?php 
                            $id="txtVenueName";
                          ?>
                          <div class="form-group">
                              <label class="control-label col-md-3 col-sm-3 col-xs-12" for="last-name">Venue Name : 
                              </label>
                              <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                <b></b>
                                <input type="text" name="<?php echo $id; ?>" id="<?php echo $id; ?>" value="<?php echo getValue($id);?>" class="form-control">
                                <span class="error"><?php echo $arrErrors['name'];?></span>
                              </div>
                          </div>
                          	<?php 
                							$id='taVenueAddress';
                						?>
    	                    <div class="form-group">
    	                        <label for="middle-name" class="control-label col-md-3 col-sm-3 col-xs-12">Venue Address : </label>
    	                        <div class="col-md-8 col-sm-8 col-xs-12">
    	                          <textarea  name="<?php echo $id; ?>" id="<?php echo $id; ?>" rows="8" class="form-control"><?php echo getValue($id);?></textarea>
    	                          <span class="error"><?php echo $arrErrors['description'];?></span>
    	                        </div>
    	                    </div>
                          <div class="ln_solid"></div>
                          <div class="form-group">
                            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                              <button class="btn btn-primary" type="submit" name="submit" onClick="return fnValidation('preview');">Preview</button></a>
                              <button type="submit" class="btn btn-success" name="submit" onClick="return fnValidation('download');">Download</button>
                            </div>
                          </div>
                        </form>
                      </div>
                      <div class="col-md-6">
                        <div class="loader-wrapper" style="width:100%;min-height:775px;" id="loader"><img src="images/loader.gif" /></div>
                        <iframe src="brochure_preview.php" name="iframePreview" id="iframePreview" style="width:100%;min-height:775px; display: none;" onload="hideIframeLoader();">
                        </iframe>
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
      // Function to load/unload iframe
      function showIframeLoader(){
        $("#loader").show();
        $("#iframePreview").hide();
      }
      function hideIframeLoader(){
        $("#loader").hide();
        $("#iframePreview").show();
      }

      // Function to show/hide form loader
      function showFormLoader(){
        $("#frmPdf").hide();
        $("#form_loader").show();
      }
      function hideFromLoader(){
        $("#frmPdf").show();
        $("#form_loader").hide();
      }

      function fnValidation(action){
        $("#hdnAction").val(action);
        $(".form-group .error").html("");

        var state=$("#lstState").val();
        var sports=$("#lstSports").val();
        var suburb=$("#txtSuburb").val();
        var dates=$("#txtDates").val();
        var season=$("#lstSeason").val();
        var year=$("#lstYear").val();
        var venueName=$("#txtVenueName").val();
        var venueAddress=$("#taVenueAddress").val();

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
        if(dates.trim()=="")
        {
          var msg="Please enter dates.";
          $("#txtDates").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(season.trim()=="")
        {
          var msg="Please enter season.";
          $("#lstSeason").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(year.trim()=="")
        {
          var msg="Please enter year.";
          $("#lstYear").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(venueName.trim()=="")
        {
          var msg="Please enter venue name.";
          $("#txtVenueName").parents(".form-group").find(".error").html(msg);
          blError=true;
        }
        if(venueAddress.trim()=="")
        {
          var msg="Please enter venue address.";
          $("#taVenueAddress").parents(".form-group").find(".error").html(msg);
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
                  $("#txtDates").val(objData.dates);
                  $("#txtVenueName").val(objData.venue_name);
                  $("#taVenueAddress").val(objData.venue_address);
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
                  var talents2='<option value="">--Select Talent 2--</option>'+objData.talents;
                  $("#lstTalent1").html(talents1);
                  $("#lstTalent2").html(talents2);
                }
              }
            });
          }
        });
      });
    </script>
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