<?php
	session_start();
	require '../conn.php';

	$json=file_get_contents('php://input');
	$obj=json_decode($json,true);

	$data=$obj["data"];
	$table=$obj["tableName"];
	$users=$obj["users"];
	$headers=$obj["headers"];
	$uploader=$_SESSION["UID"];

	$createQry="CREATE TABLE ".$table."( 
		UID INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,";

	foreach ($headers as $key) {
		$createQry.="`".$key."` VARCHAR(255), ";		
	}
	
	$createQry.="PRIMARY KEY(UID));";
	$alterQry="ALTER TABLE ".$table." AUTO_INCREMENT=100000;";
	$tableQry="INSERT INTO database_names (`tables`, `uploader`) VALUES ('".$table."','".$uploader."');";
	
	if($conn->query($createQry)){
		if($conn->query($alterQry)){
			if($conn->query($tableQry)){
				//table created
			}
			else
				die($conn->error);
		}
		else
			die($conn->error);
	}
	else
		die($conn->error);
	
	//section for inserting values;
	foreach ($data as $tuple) {
		$insertQry="INSERT INTO `".$table."` (";
		$temp="(";
		foreach ($tuple as $key => $value) {
			$insertQry.="`".$key."`, ";
			$temp.="'".$value."', ";
		}
		$temp= substr_replace($temp,"",-2);
		$insertQry= substr_replace($insertQry,"",-2);
		$insertQry.=") VALUES ".$temp.");";
		
		if($conn->query($insertQry)){				
			//inserted
		}
		else
			die($conn->error);
	}
	
	//section below is to add user view/edit rights
	foreach ($users as $tuple) {
		$selQuery="SELECT tables FROM users WHERE UID='".$tuple["UID"]."'";
		$result=$conn->query($selQuery);
		$row=$result->fetch_assoc();
		$utable=json_decode($row["tables"],true);
		if($tuple["edit"]=="yes"){
			$view=array('name'=>$table);
			$edit=array('name'=>$table);
			array_push($utable["view"], $view);
			array_push($utable["edit"], $edit);
			$utable=array('view'=>$utable["view"],'edit'=>$utable["edit"]);
			$utable=json_encode($utable);
			$updateQry="UPDATE users SET tables='".$utable."' WHERE UID='".$tuple["UID"]."'";
			if(!$conn->query($updateQry)){
				die($conn->error);
			}
			continue;
		}
		if($tuple["view"]=="yes"){
			$view=array('name'=>$table);
			array_push($utable["view"], $view);
			$utable=array('view'=>$utable["view"],'edit'=>$utable["edit"]);
			$utable=json_encode($utable);
			$updateQry="UPDATE users SET tables='".$utable."' WHERE UID='".$tuple["UID"]."'";
			if(!$conn->query($updateQry)){
				die($conn->error);
			}
			continue;
		}
	}

	echo "Successfully uploaded to DB";
		
