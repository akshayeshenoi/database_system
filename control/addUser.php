<?php

	require '../conn.php';

	$json=file_get_contents('php://input');

	$data=json_decode($json,true);

	$username=$data["username"];
	$password=$data["password"];
	$name=$data["name"];
	$table=$data["table"];
	$admin=$data["admin"];

	$table=json_encode($table);

	$query="INSERT INTO users(username, password, name, tables, admin) VALUES ('".$username."','".$password."','".$name."','".$table."','".$admin."');";

	if($conn->query($query)){
		echo "Success";
	}

	else
		echo $conn->error;