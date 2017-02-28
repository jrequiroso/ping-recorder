<?php
	header("Refresh: 300");
	
	require_once('functions.php');

	if (isset($_GET['date']) && !empty($_GET['date']))
		$date = $_GET['date'];
	else {
		//get latest record
		$date = date('Y-m-d');
	}

	$all_results = getPingResults($date);

	$dates_available = getAvailableDates();
?>

<!DOCTYPE html>
<html>
<head>
	<title>PLDT Latency Monitor</title>
	<link rel="stylesheet" href="css/bulma.css">
	<link rel="stylesheet" href="js/jquery-ui/jquery-ui.min.css">
	<link rel="stylesheet" href="css/css.css">
</head>
<body>
	<div class="container">
		<form class="search-form" method="get" action="">
			<div class="control is-horizontal">
				<p class="control has-addons-centered has-addons">
					<input class="input" type="date" name="date" id="date" value="<?= (isset($_GET['date']))?$_GET['date']:date("Y-m-d") ?>" placeholder="Select Date"> 
					<button class="button is-primary" type="submit"> View</button>
				</p>
			</div>
		</form>
		<div class="is-half is-offset-one-quarter">
			<table class="stats-table" border="1">
				<tr>
					<th colspan="3" class="has-text-centered">Stats for <?= date('d F Y (l)', strtotime($all_results['highest']['datetime'])) ?></th>
				</tr>
				<tr>
					<th>Highest Ping</th>
					<td><?= date('h:i A', strtotime($all_results['highest']['datetime'])) ?></td>
					<td><?= $all_results['highest']['ping'] ?> ms</td>
				</tr>
				<tr>
					<th>Lowest Ping</th>
					<td><?= date('h:i A', strtotime($all_results['lowest']['datetime'])) ?></td>
					<td><?= $all_results['lowest']['ping'] ?> ms</td>
				</tr>
				<tr>
					<th>Average Ping</th>
					<td colspan="2"><?= $all_results['average_ping'] ?> ms</td>
				</tr>
			</table>
		</div>
	</div>
	<div id="chartContainer"></div>
	<script src="js/jquery.min.js"></script>
	<script src="js/jquery-ui/jquery-ui.min.js"></script>
	<script src="js/canvasjs-1.9.6/canvasjs.min.js"></script>
	<script>
	var dates_available = [
			"<?= implode('","', $dates_available) ?>"
		]
	
	function check_available_date( date )
	{
	    var formatted_date = '', ret = [true, "", ""];
	    if (date instanceof Date) {
	        formatted_date = $.datepicker.formatDate( 'yy-mm-dd', date );
	    } else {
	        formatted_date = '' + date;
	    }
	    if ( -1 === dates_available.indexOf(formatted_date) ) {
	        ret[0] = false;
	    }
	    return ret;
	}
	window.onload = function() {
		$("#date").datepicker({
				"dateFormat" : "yy-mm-dd",
				"beforeShowDay": check_available_date
			});

		var chart = new CanvasJS.Chart("chartContainer", {
			title: {
				text: "PLDT Latency Monitor"
			},
			axisX: {
				title: "Time",
				labelFontSize: 10,
				titleFontSize: 12
			},
			axisY: {
				title: "Ping (ms)",
				labelFontSize: 10,
				titleFontSize: 12
			},
			data: [{
				type: "line",
				dataPoints: [<?php
					echo "\n";
					foreach ($all_results['results'] as $key => $result) {
						echo "\t\t\t\t\t{x: new Date('".$result['ping_datetime']."'),y: ".$result['ping_time']."},\n";
					}
				?>
				]
			}]
		});
		chart.render();
	}
	</script>
</body>

</html>