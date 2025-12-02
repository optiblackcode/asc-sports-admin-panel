<?php
$isLoginPage=1;
include "include/common.php";
$arrCategories=getCategories();
$objDatewiseEvents=new ZOHO_DATEWISE_EVENTS();
$objParticipants=new ZOHO_PARTICIPANTS();
$objSeasonDates=new ZOHO_SEASON_DATES();
$season="Summer";
$year="2021";
$previousYear=$year-1;
$rsltDatewiseEvents=$objDatewiseEvents->getEventsBySeasonYear($season,$year);
$maxDay=$objParticipants->getMaxDayFromSeasonYear($season,$previousYear);

// Get first date and day of week
$arrDaysText=[
	'0'=>[
		'short'=>'S'
	],
	'1'=>[
		'short'=>'M'
	],
	'2'=>[
		'short'=>'T'
	],
	'3'=>[
		'short'=>'W'
	],
	'4'=>[
		'short'=>'T'
	],
	'5'=>[
		'short'=>'F'
	],
	'6'=>[
		'short'=>'S'
	]
];

$firstDate="";
$firstDay="";
$rsltSeasonStartDate=$objSeasonDates->getBookingStartDateWithSeasonYear($season."_".$year);
if($rsltSeasonStartDate)
{
	if($rowSeasonStartDate=$rsltSeasonStartDate->fetch_assoc())
	{
		if($rowSeasonStartDate['booking_start_date']!="0000-00-00")
		{
			$firstDate=$rowSeasonStartDate['booking_start_date'];
			$firstDay=date("N",strtotime($firstDate));
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Events</title>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Montserrat:300,400,600,700&amp;lang=en" />
	<style type="text/css">
		body
		{
			background-color:#0d2237cd;
			color:#d9d9d9;
			font-family:Montserrat;
			font-size: 14px;
		}
		/*
		.description{
		    position: absolute;
		    background: aliceblue;
		    width: 150px;
		    padding: 10px;
		    border-radius: 10px;
		    top: 27px;
		    left: 2px;
		    display: none;
		    font-size: 13px;
		    z-index:999;
		}
		.description:before{
			position: absolute;
		  	top: -23px;
		  	height: 10px;
		  	left: 5px;
		  	border: 7px solid transparent;
		  	border-bottom-color: aliceblue;
		  	content: "";
		}
		*/
		.description{
			border: solid 1px #bdbdbd;
		    border-radius: 2px;
		    background-color: white;
		    position: absolute;
		    padding:10px;
		    top: 27px;
		    left: 2px;
		    box-shadow: 0px 2px 2px 0px rgba(204,204,204,0.6);
		    font-size: 14px;
		    -moz-box-shadow: 0px 2px 2px 0px rgba(204,204,204,0.6);
		    -webkit-box-shadow: 0px 2px 2px 0px rgba(204,204,204,0.6);
		    z-index: 10000;
		    word-wrap: break-word;
		    width: 250px;
    		pointer-events: none;
    		overflow: hidden;
    		display: none;
    		color: rgb(0, 0, 0);
		}
		.description.small
		{
			min-width: 140px;
			width:auto;
		}
		.cell img{
			height: 20px;
		}
		.cell{
			position:relative;
			cursor: pointer;
		}
		.cell:hover .description{
			display: block;
		}
		.cell:focus .description{
			display: block;
		}
		.cell:focus{
			outline: none;
		}
		table{
			border:1px solid #d9d9d9;
			border-collapse: collapse;
			border-radius: 10px;
			background-color: #223646;
			table-layout: fixed;
		}
		td{
			height: 20px;
			min-width: 20px;
			text-align:center;
			padding: 2px;
			border-top:1px solid #d9d9d9;
			border-bottom:1px solid #d9d9d9;
			border-left:1px solid #d9d9d94d;
			border-right:1px solid #d9d9d94d;
		}
		.description .title{
			margin-bottom:5px;
		}
		td.highlighted{
			background-color: #284053;
		}
		td.highlighted.heading{
			background-color: #006C75;
		}
		td.highlighted.heading.current{
			background-color: #00C9DB;
		}
	</style>
</head>
<body>
	<?php
if($rsltDatewiseEvents)
{
	?>
	<table>
		<tr style="background-color: #00838f; cursor: pointer;">
			<td>Weekday</td>
			<?php
			$d=1;
			$currentDate=date('Y-m-d H:i:s',strtotime("+ 11 Hours"));
			$currentDate=date('Y-m-d',strtotime($currentDate));
			while($d<=$maxDay)
			{
				$dayNumber=($d+($firstDay-1))%7;
				$dateForDay=date("Y-m-d",strtotime($firstDate." + ".($d-1)." Days"));
				if($dateForDay==$currentDate)
				{
					?>
					<td class="highlighted heading current">
					<?php
				}
				else
				{
					if($dayNumber=='0' || $dayNumber=='6')
					{
					?>
						<td class="highlighted heading">
					<?php	
					}
					else
					{
					?>
						<td>
					<?php	
					}
				}
				?>
				<div class="cell" tabindex="1">
					<?php echo $arrDaysText[$dayNumber]['short'];?>
						<div class="description small">
							<b>
								<?php echo format_date($dateForDay,"D - d M,Y");?>
							</b>
						</div>
					</td>
				</div>
				<?php
				$d++;
			}
			?>
		</tr>
		<tr style="background-color: #00838f;">
			<td>Category/Day</td>
			<?php
			mysqli_data_seek($rsltDatewiseEvents,0);
			// First collect all events in one array with adding blanks where no events available
			$arrAllDateweiseEvents=[];
			$d=1;
			while ( $rowDatewiseEvents=$rsltDatewiseEvents->fetch_assoc()) {
				while($d<$rowDatewiseEvents['day_of_season'])
				{
					$d++;
				}
				$arrAllDateweiseEvents[$d]=$rowDatewiseEvents;
				$d++;
			}

			// Loop from 1st to max day
			$d=1;
			while ($d<=$maxDay) {
				$dayNumber=($d+($firstDay-1))%7;
				if($dayNumber=="0" || $dayNumber=="6")
				{
					?>
						<td class="highlighted heading"><?php echo $d;?></td>
					<?php
				}
				else
				{
					?>
						<td><?php echo $d;?></td>
					<?php
				}
				$d++;
			}
			?>
		</tr>
		<?php
			foreach ($arrCategories as $catDbName => $catName) 
			{
			?>
				<tr>
					<td><?php echo $catName; ?></td>
					<?php
						mysqli_data_seek($rsltDatewiseEvents,0);
						$d=1;
						while ($d<=$maxDay) {
							$dayNumber=($d+($firstDay-1))%7;
							if($dayNumber=="0" || $dayNumber=="6")
							{
								?>
									<td class="highlighted">
								<?php
							}
							else
							{
								?>
									<td>
								<?php
							}
							if(isset($arrAllDateweiseEvents[$d]))
							{
								$rowDatewiseEvents=$arrAllDateweiseEvents[$d];
								if($rowDatewiseEvents[$catDbName]!=""){
									?>
									<div class="cell" tabindex="1">
										<img src="http://31.220.55.121/portal/images/green_tick.png">
										<div class="description">
											<div class="title"><b><?php echo format_date($rowDatewiseEvents['event_date'],"D - d M,Y");?></b></div>
											<?php echo $rowDatewiseEvents[$catDbName];?>
										</div>
									</div>
									<?php
									};
							}
							?>
								</td>
							<?php
							$d++;
						}	
						?>
				</tr>
			<?php
			}
		?>
	</table>
	<?php
}
else
{
	echo "Error in query.";
}
?>
</body>
</html>
