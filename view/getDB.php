<?php
	session_start();
	$uid=$_SESSION["UID"];

	require '../conn.php';

	$query="SELECT tables FROM users WHERE UID='".$uid."';";

	$result=$conn->query($query);
	while($row = $result->fetch_assoc()){
		$jo=$row["tables"];					//The list of tables is stored as a json object					
		echo $jo;
	}
?>
