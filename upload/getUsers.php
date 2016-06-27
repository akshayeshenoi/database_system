<?php
	require '../conn.php';

	$query="SELECT name, UID FROM users";
	$data=[];
	$result=$conn->query($query);
	while($row = $result->fetch_assoc()){
		array_push($data,$row); 		
	}
	$jo=array('data'=>$data);
	$jo=json_encode($jo);
	echo $jo;