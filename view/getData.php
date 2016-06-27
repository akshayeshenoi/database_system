<?php
	$table=$_GET['db'];

	require '../conn.php';

	$query="SELECT * FROM ".$table.";";
	$query2 ="SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='test' AND `TABLE_NAME`='".$table."';";

	$result=$conn->query($query);
	$result2=$conn->query($query2);
	
	$cnames=array();
	
	while($row=$result2->fetch_assoc()){
		array_push($cnames, $row['COLUMN_NAME']);
	}

	$response="<thead><tr>";
	foreach ($cnames as $name) {
			$response.="<th>".$name."</th>";
		}
	$response.="</thead></tr>";

	while($row=$result->fetch_assoc()){
		$response.="<tr>";
		foreach ($cnames as $name) {
			$response.="<td>".$row[$name]."</td>";
		}
		$response.="</tr>";
	}
	echo $response;
?>