<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){

	$json=file_get_contents('php://input');
	$data=json_decode($json,true);

	require '../conn.php';

	$username=$data["username"];
	$password=$data["password"];

	$query="SELECT * FROM users WHERE username='".$username."';";

	if ($conn->connect_error) {
	    die("Database connection failed: " . mysqli_connect_error());
	}

	$result=$conn->query($query);

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			if($row["password"]==$password){
				session_start();
				$_SESSION['UID']=$row["UID"];
				$_SESSION['name']=$row['name'];
				$_SESSION['admin']=$row['admin'];
				//header("location:view.php");
				$result= array('status'=>'success');
				echo json_encode($result);
				break;
			}
			else{
				$result= array('status'=>'fail');
				echo json_encode($result);
				break;
			}
		}
	}
	else{
		$result= array('status'=>'fail');
		echo json_encode($result);
	}
}

?>