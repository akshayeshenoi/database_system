<?php
	$json=file_get_contents('php://input');
	$data=json_decode($json,true);
	//echo $json;
	$set="";
	$update="";
	foreach($data as $key => $value){
		if($key=='table'){
			$table= $value;
			continue;
		}
		if($key=='UID'){
			$UID=$value;
			continue;
		}
		$set.="`".$key."`='".$value."',";
	}
	$set= chop($set,",");
	$query="UPDATE ".$table." SET ".$set." WHERE UID='".$UID."';";
	//echo $query;
	require '../conn.php';

	if ($conn->query($query) === TRUE) {
    	echo "Record updated successfully";
	} else {
    	 //echo "Error updating record";
    	 echo $conn->error;
	}

$conn->close();
?>