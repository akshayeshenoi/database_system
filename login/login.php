<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

	$json=file_get_contents('php://input');
	$data=json_decode($json,true);

	require '../conn.php';

	if ($conn->connect_error) {
	    die("Database connection failed: " . mysqli_connect_error());
	}

	$username=$data["username"];
	$password=$data["password"];

	$qry = 'SELECT UID,name,admin FROM users WHERE username = ? AND password = ?';
	$stmt = $conn->prepare($qry);
	$stmt->bind_param("ss", $username, $password);

	$stmt->execute();
	$stmt->bind_result($UID,$name,$admin);

	while ($stmt->fetch()) {
        session_start();
		$_SESSION['UID']=$UID;
		$_SESSION['name']=$name;
		$_SESSION['admin']=$admin;
		$result= array('status'=>'success');
		echo json_encode($result);
		die();
    }
	//else
    $result= array('status'=>'fail');
	echo json_encode($result);	
}
?>
