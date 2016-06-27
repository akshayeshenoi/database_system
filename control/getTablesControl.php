<?php
	require '../conn.php';

	$query="SELECT tables FROM database_names";
	$tnames=[];
	$result=$conn->query($query);
	while($row = $result->fetch_assoc()){
		foreach ($row as $key => $value) {
			array_push($tnames,$value); 
		}
	}
	$jo=array('tables'=>$tnames);
	$jo=json_encode($jo);
	echo $jo;