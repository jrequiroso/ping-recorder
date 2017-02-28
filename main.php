<?php
	header("Refresh: 300");

	$now = date("Y-m-d H:i:s");
	$local_ip = getHostByName(getHostName());
	$ping_result = exec("ping -n 1 googlaasdasdase.com | FIND \"TTL=\"");
	$time = 0;
	$host_ip = "";

	preg_match('/Reply from (.*?):/', $ping_result, $ip_match);
	preg_match('/time=(.*?)ms/', $ping_result, $time_match);

	if (array_key_exists(1, $time_match))
		$time = $time_match[1];

	if (array_key_exists(1, $ip_match))
		$host_ip = $ip_match[1];

	$mysql_connection = new mysqli("localhost", "root", "", "pinger");

	if($mysql_connection->connect_error){
	    die("ERROR: Could not connect. " . mysqli_connect_error());
	}

	$stmt = $mysql_connection->prepare("INSERT INTO data (local_ip, host_ip, ping_time, ping_data) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssss", $local_ip, $host_ip, $time, $ping_result);
	$stmt->execute();

	echo "Records inserted successfully.";

	mysqli_close($mysql_connection);
