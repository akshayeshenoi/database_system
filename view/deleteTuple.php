<?php
	$json=file_get_contents('php://input');
	$data=json_decode($json,true);

	$UID = $data["UID"];
	$table = $data["table"];
	
	$query = "DELETE FROM `". $table. "` WHERE UID =".$UID.";";
	require '../conn.php';

	if ($conn->query($query) === TRUE) {
    	echo "Record deleted successfully";
	} else {
    	 //echo "Error updating record";
    	 echo $conn->error;
	}

	$conn->close();
?>