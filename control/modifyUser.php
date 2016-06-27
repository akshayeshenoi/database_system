<?php

	require '../conn.php';



	$json=file_get_contents('php://input');

	$data=json_decode($json,true);



	$UID=$data["UID"];

	$username=$data["username"];

	$password=$data["password"];

	$name=$data["name"];

	$table=$data["tables"];

	$admin=$data["admin"];

	$table=json_encode($table);



	$query="UPDATE users SET username='".$username."', password='".$password."', name='".$name."', admin='".$admin."', tables='".$table."' WHERE UID='".$UID."'";



	if($conn->query($query)){

		echo "Success";

	}

	else

		echo $conn->error;

?>