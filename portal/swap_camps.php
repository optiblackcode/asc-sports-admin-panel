<?php
	$url = "https://shop.australiansportscamps.com.au/wp-json/newasc/v2/get_order_swap";
	$ch = curl_init();  
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $output=curl_exec($ch);
	$result = json_decode($output);
    curl_close($ch);
    $result = json_decode(json_encode($result), true);
	$res = $result['ResponseData'];
	
	$url_camp = "https://shop.australiansportscamps.com.au/wp-json/newasc/v2/get_current_camps";
	$ch_camp = curl_init();  
    curl_setopt($ch_camp,CURLOPT_URL,$url_camp);
    curl_setopt($ch_camp,CURLOPT_RETURNTRANSFER,true);
    $output_camp=curl_exec($ch_camp);
	$result_camp = json_decode($output_camp);
    curl_close($ch_camp);
    $result_camp = json_decode(json_encode($result_camp), true);
	$res_camp = $result_camp['ResponseData'];
	
	

	
	
	
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
                <h3>Swap camps</h3>
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
                      
                      <form id="frmShedule" action="swap_camps_process.php" class="form-horizontal form-label-left" method="POST" enctype="multipart/form-data"  >
                          <div id="loading">
                          <img src="images/giphy.gif" alt="loader" style="display:block; position:absolute; z-index:100; left:50%; top:100px" id="loaderImg">
                          </div>
                          
                          <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="camp">Order : 
                                  </label>
                                  <div class="col-md-8 col-sm-8 col-xs-12 control-label" style="text-align: left;">
                                    <b></b>
                                    <span class="error"><?php echo $err;?></span>
                                    <select class="form-control select2-js orderselect" name="order" id="order">
                                      <option value="">--Select Order--</option>
										<?php
											foreach($res as $r){
										?>
											<option value="<?php echo $r['OrderID']; ?>"><?php echo $r['OrderName']; ?></option>
										<?php	
											}
										?>
                                          
                                      
                                    </select>
                                    <span class="error"></span>
                                  </div>
                              </div>
							  <div class="form-group tbl">
                              </div>
							  
                          
                        </div>
                        
                          <div class="form-group">
                            <div class="col-md-8 col-sm-8 col-xs-12 col-md-offset-3">
                              <button class="btn btn-primary" type="submit" name="submit">Swap Camps</button></a>
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
    <script>
    $('.orderselect').on('change', function() {
		var val = $(this).val();
		if(val == ""){
			alert("Please select the order");
			return false;
		}
		$(".tbl").html("");		
		$.ajax({
			url: "https://shop.australiansportscamps.com.au/wp-json/newasc/v2/get_order/"+val, 
			crossDomain: true,
			dataType: "json",
			type: "get",
			success: function (data, textStatus, jqXHR) {
				console.log(data);
				console.log(textStatus);
				console.log(jqXHR);
				var data = data.ResponseData;
				console.log(data);
				var html ='';
				html+= '<table class="table table-striped" width="100%">';
				
				html+= '<tr>'
				html+='<th><h4>Parent/Order Information</h4></th>';
				html+='<td></td>';
				html+='</tr>';
				html+= '<tr>'
				
				html+='<th>OrderID</th>';
				html+='<td># '+data.OrderID+'</td>';
				html+='</tr>';
				
				html+= '<tr>'
				html+='<th>Order Sub Total</th>';
				html+='<td>'+data.OrderSubtotal+'</td>';
				html+='</tr>';
				
				html+= '<tr>'
				html+='<th>Order Discount</th>';
				html+='<td>'+data.OrderDiscount+'</td>';
				html+='</tr>';
				
				html+= '<tr>'
				html+='<th>Order Total</th>';
				html+='<td>'+data.OrderTotal+'</td>';
				html+='</tr>';
				
				html+= '<tr>'
				html+='<th>Parent Name</th>';
				html+='<td>'+data.OrderFname+" "+data.OrderLname+'</td>';
				html+='</tr>';
				
				html+= '<tr>'
				html+='<th>Parent Email</th>';
				html+='<td>'+data.OrderEmail+'</td>';
				html+='</tr>';
				
				html+= '<tr>'
				html+='<th><h4>Child Information</h4></th>';
				html+='<td></td>';
				html+='</tr>';
				var products = data.products;
				
				$.each(products, function (key, val) {
					html+= '<tr>'
					html+='<th><h5>CHILD '+(key+1)+' INFO</h5></th>';
					html+='<td></td>';
					html+='</tr>';
					html+= '<tr>'
					html+='<th>Camp Name</th>';
					html+='<td>'+val.ProductName+'</td>';
					html+='</tr>';
					
					html+= '<tr>'
					html+='<th>Child Name</th>';
					html+='<td><input type="hidden" name="oldids[]" value="'+val.ID+'" /><input type="hidden" name="Name[]" value="'+val.Name+'" /><input type="hidden" name="old_string[]" value="'+val.Name+' : '+val.ProductName+' " /><input type="hidden" name="child[]" value="'+val.ChildID+'" />'+val.Name+'</td>';
					html+='</tr>';
					
					html+= '<tr>'
					html+='<th>Child DOB</th>';
					html+='<td>'+val.DOB+'</td>';
					html+='</tr>';
					
					html+= '<tr>'
					html+='<th>Child Gender</th>';
					html+='<td>'+val.Gender+'</td>';
					html+='</tr>';
					
					html+= '<tr>'
					html+='<th>Swap Camp</th>';
					html+='<td>';
					html+='<select class="form-control select2" name="camp[]" id="camp">';
                    html+=' <option value="">--Select Camp--</option>';
						
						<?php
							foreach($res_camp as $rcamp){
						?>
							var idd = <?php echo $rcamp['ID']; ?>;
							var str = '';
							if(val.ID == idd){
								str ='selected="selected"';
							}
							html+="<option "+str+" value=\"<?php echo $rcamp['ID']; ?>\"><?php echo $rcamp['Name']; ?></option>";
						<?php	
							}
						?>
					html+='</select>';	
					html+='</td>';
					html+='</tr>';
					
				});
				
				
				
				html+= '</table>';
				
				$(".tbl").html(html);
				$('.select2').select2();
				$(".select2-js").select2('destroy'); 
				//$(".select2-container").remove(); 
				$(".select2-js").select2(); 
			}
			
		});			
	});
	</script>
	
	
	</body>
</html>
