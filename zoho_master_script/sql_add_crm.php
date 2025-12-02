<?php
ini_set('max_execution_time', 10800); // 3 Hour
ini_set("memory_limit", "-1");
set_time_limit(0);
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css"/>
  <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="table-responsive">
    <table class="table table-bordered" id="example" width="100%" cellspacing="0">
<thead>
<tr>
<th>rec_id</th>
<th>participant_id</th>
<th>booking_id</th>
<th>camp_id</th>
<th>child_id</th>
<th>booking_status</th>
<th>camp_sku</th>
<th>business_arm</th>
<th>dob</th>
<th>age</th>
<th>gender</th>
<th>booking_date</th>
<th>booking_date_time</th>
<th>participant_type</th>
<th>is_partner</th>
<th>season</th>
<th>year</th>
<th>b_state</th>
<th>b_suburb</th>
<th>camp_name</th>
<th>camp_group</th>
<th>camp_suburb</th>
<th>camp_state</th>
<th>sports</th>
<th>venue_name</th>
<th>venue_booked_unique_id</th>
<th>camp_unique_id</th>
<th>parent_type</th>
<th>family_type</th>
</tr>
</thead>
<tbody>
  <?php
  include 'conn.php';
  //print_r($conn);
 
  $query = mysqli_query($conn,"SELECT * FROM zoho_master WHERE booking_date >= '2019-01-01'");
   while($row = mysqli_fetch_assoc($query))
        {

          
            //print_r($row);
?>
  <tr>
    <td><?php echo $row['rec_id'];?></td>
    <td><?php echo "zcrm_".$row['participant_id'];?></td>
    <td><?php echo "zcrm_".$row['booking_id'];?></td>
    <td><?php echo "zcrm_".$row['camp_id'];?></td>
    <td><?php echo "zcrm_".$row['child_id'];?></td>
    <td><?php echo $row['booking_status'];?></td>
    <td><?php echo $row['camp_sku'];?></td>
    <td><?php echo $row['business_arm'];?></td>
    <td><?php echo $row['dob'];?></td>
    <td><?php echo $row['age'];?></td>
    <td><?php echo $row['gender'];?></td>
    <td><?php echo $row['booking_date'];?></td>
    <td><?php echo $row['booking_date_time'];?></td>
    <td><?php echo $row['participant_type'];?></td>
    <td><?php echo $row['is_partner'];?></td>
    <td><?php echo $row['season'];?></td>
    <td><?php echo $row['year'];?></td>
    <td><?php echo $row['b_state'];?></td>
    <td><?php echo $row['b_suburb'];?></td>
    <td><?php echo $row['camp_name'];?></td>
    <td><?php echo $row['camp_group'];?></td>
    <td><?php echo $row['camp_suburb'];?></td>
    <td><?php echo $row['camp_state'];?></td>
    <td><?php echo $row['sports'];?></td>
    <td><?php echo $row['venue_name'];?></td>
    <td><?php echo $row['venue_booked_unique_id'];?></td>
    <td><?php echo $row['camp_unique_id'];?></td>
    <td><?php echo $row['parent_type'];?></td>
    <td><?php echo $row['family_type'];?></td>
  </tr>
  <?php
          
          }
        ?>
</tbody>
</table>
</div>

<script>
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            /*'copyHtml5',*/
            /*'excelHtml5',*/
            /*'csvHtml5'*/
            /*'pdfHtml5'*/
            {
                extend: 'excelHtml5',
                title: 'Zoho Master Export'
            },
            {
                extend: 'csvHtml5',
                title: 'Zoho Master Export'
            }
        ]
    } );
} );
</script>
</body>
</html>