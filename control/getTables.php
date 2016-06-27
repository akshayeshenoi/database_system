<?php
	require '../conn.php';

	$query="SELECT database_names.UID, database_names.tables, name FROM database_names,users WHERE users.UID=database_names.uploader";

	$result=$conn->query($query);
	$data=array();
	while ($row=$result->fetch_assoc()) {
		array_push($data, $row);
	}
	$json=array("data"=>$data);
	echo json_encode($json);