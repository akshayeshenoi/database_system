<?php
	//UID not required for this operation, even though it is in the json(blank field),since it is dynamically generated.
	$json=file_get_contents('php://input');
	$data=json_decode($json,true);
	//echo $json;
	$columns="";
	$values="";
	foreach($data as $key => $value){
		if($key=='table'){
			$table= $value;
			continue;
		}
		if($key=='UID'){
			continue;
		}
		$columns.="`".$key."`,";
		$values.="'".$value."',";
	}
	$columns= chop($columns,",");
	$values= chop($values,",");
	//$query="UPDATE ".$table." SET ".$set." WHERE UID='".$UID."';";
	$query="INSERT INTO `".$table."` (".$columns.") VALUES (".$values.");";
	require '../conn.php';

	if ($conn->query($query) === TRUE) {
    	echo "Record updated successfully";
	} else {
    	 //echo "Error updating record";
    	 echo $conn->error;
	}

	$conn->close();
?>