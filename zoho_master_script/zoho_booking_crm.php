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
<th>booking_id</th>
<th>family_id</th>
<th>parent_id</th>
<th>booking_status</th>
<th>status</th>
<th>b_suburb</th>
<th>b_suburb_auto</th>
<th>b_state</th>
<th>b_state_auto</th>
<th>b_city_auto</th>
</tr>
</thead>
<tbody>
  <?php
  include 'conn.php';
  //print_r($conn);
 
  $query = mysqli_query($conn,"SELECT * FROM zoho_bookings WHERE date(created_at) >= '2019-01-01'");
   while($row = mysqli_fetch_assoc($query))
        {

          
            //print_r($row);
?>
  <tr>
    <td><?php echo $row['rec_id'];?></td>
    <td><?php echo "zcrm_".$row['booking_id'];?></td>
    <td><?php echo "zcrm_".$row['family_id'];?></td>
    <td><?php echo "zcrm_".$row['parent_id'];?></td>
    <td><?php echo $row['booking_status'];?></td>
    <td><?php echo $row['status'];?></td>
    <td><?php echo $row['b_suburb'];?></td>
    <td><?php echo $row['b_suburb_auto'];?></td>
    <td><?php echo $row['b_state'];?></td>
    <td><?php echo $row['b_state_auto'];?></td>
    <td><?php echo $row['b_city_auto'];?></td>
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
                title: 'Booking Master Export'
            },
            {
                extend: 'csvHtml5',
                title: 'Booking Master Export'
            }
        ]
    } );
} );
</script>
</body>
</html>