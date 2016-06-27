<?php
	require '../conn.php';
	$json=file_get_contents('php://input');
	$uid=json_decode($json,true);	//gets the uid of the table

	//this gets the name of the table from the UID
	$qry="SELECT tables FROM database_names WHERE UID=".$uid.";";		
	$tres=$conn->query($qry);
	$trow=$tres->fetch_assoc();
	$tname=$trow["tables"];

	//everything below updates all the users' table rights(removing entry from everywhere)
	$searchQry="SELECT users.UID, users.tables FROM users WHERE users.tables LIKE CONCAT('%',(select database_names.tables FROM database_names WHERE database_names.UID=".$uid."),'%')";
	$result=$conn->query($searchQry);
	while($row=$result->fetch_assoc()){
		$tables=$row["tables"];
		$tables=json_decode($tables, true);
		
		$view=$tables["view"];
		$i=0;
		foreach ($view as $key) {
			if($key["name"]==$tname){
				unset($view[$i]);
			}
			$i++;
		}
		$tables["view"]=array_values($view);

		$edit=$tables["edit"];
		$i=0;
		foreach ($edit as $key) {
			if($key["name"]==$tname){
				unset($edit[$i]);
			}
			$i++;
		}
		$tables["edit"]=array_values($edit);

		$tables=json_encode($tables, true);
		//updates the table column
		$updateQry="UPDATE users SET tables='".$tables."' WHERE UID=".$row["UID"].";";
		$conn->query($updateQry);
	}

	//this deletes the entry from the database_names table
	$deleteQry="DELETE FROM `database_names` WHERE UID=".$uid.";";
	$conn->query($deleteQry);
	//drops table
	$tDeleteQry="DROP TABLE ".$tname;
	$conn->query($tDeleteQry);

	echo "Deleted ".$tname;