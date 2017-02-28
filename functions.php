<?php

/**
 * 
 */
function doQuery($sql)
{
    $link = mysqli_connect("localhost", "root", "", "pinger");

	if($link === false){
	    die("ERROR: Could not connect. " . mysqli_connect_error());
	}

	$result = $link->query($sql);
	
	mysqli_close($link);

	return $result;
}

function getAvailableDates()
{
	$available_dates = array();

	$sql = "SELECT DISTINCT(date(`ping_datetime`)) FROM `data` ";
	$result = doQuery($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_array()) {
			$available_dates[] = $row[0];
		}
	}
	return $available_dates;

}
/**
 * 
 */
function getPingResults($date)
{
    $results = array();
	$all_results = array();
	$sql = "SELECT * FROM `data` WHERE date(`ping_datetime`) = '".$date."' ";

    $result = doQuery($sql);

	if ($result->num_rows > 0) {
		$highest = 0;
		$highest_date = null;
		$lowest = 9999;
		$lowest_date = null;
		$average = null;
		$count = mysqli_num_rows($result);
	    
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	$results[] = $row;
	    	$average += $row['ping_time'];

	    	if ($row['ping_time'] > $highest) {
	    		$highest = $row['ping_time'];
	    		$highest_date = $row['ping_datetime'];
	    	}

	    	if ($row['ping_time'] > 0 && $row['ping_time'] < $lowest) {
	    		$lowest = $row['ping_time'];
	    		$lowest_date = $row['ping_datetime'];
	    	}

	        //echo $row["ping_datetime"]. " ---" . $row["ping_time"]. "<br>";
	    }

	    $average = number_format($average / $count, 2);
		$all_results['results'] = $results;
		$all_results['highest']['ping'] = $highest;
		$all_results['highest']['datetime'] = $highest_date;
		$all_results['lowest']['ping'] = $lowest;
		$all_results['lowest']['datetime'] = $lowest_date;
		$all_results['average_ping'] = $average;
	}

	
	return $all_results;
}