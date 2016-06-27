<?php
	session_start();
	if(!isset($_SESSION["UID"])){
		header("location: index.php");
	}
	require 'conn.php';
	 $flag=true;

	$query="SELECT admin FROM users WHERE UID='".$_SESSION["UID"]."';";

	if($result=$conn->query($query)){
		while($row = $result->fetch_assoc()){
			if($row["admin"]=="no"){
				echo "<h1> You don't have admin rights </h1><br><a href='view.php'>Go back</a>";
				$flag=false;
			}
		}
	}
	if($flag==true){
?>

<!DOCTYPE html>
<html>
<head>
	<title>Control Panel</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.10/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.1.0/css/select.dataTables.min.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.1.0/js/dataTables.select.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/control.css">
	<link href='https://fonts.googleapis.com/css?family=Quattrocento+Sans' rel='stylesheet' type='text/css'>
</head>
<body>
	<div class='header'>
		<img id='logo' src="images/logo.png">
		<img id='dsm_logo' src="images/dsm_logo.png">
	</div>
	<div class="nav">
		<li><button id='add'>Add</button></li>
		<li><button id='modify'>Modify</button></li>
		<li><button id='dbBtn'>Databases</button></li>
		<li><button onclick="location.href='view.php';">Back</button></li>
		<li><button onclick="location.href='logout.php';">Logout</button></li>
	</div>
	<div id='addPane'>
		<div class="subtitle">
			add user
		</div>
		<form>
			<div class='instr'>
				Enter name<br>Enter username<br>Enter password<br>Admin<br>
			</div>
			<div class='inputs'>
				<input type="text" id="name"></input><br>
				<input type="text" id="username"></input><br>
				<input type="text" id="password"></input><br>
				<input type="radio" name="admin" value="yes">Yes<t><br>
				<input type="radio" name="admin" value="no">No<t><br>				
			</div>
			<div class="TableList" style="width:400px;">
		      <div class='pane'>
		      	<div class="paneTitle">View</div><div class='viewList'></div>
		      </div>
		      <div class='space1'></div>
		      <div class='pane'>
		      	<div class="paneTitle">Edit</div><div class='editList'></div>
		      </div>
	  		</div>
			<input id="submit" onclick="addUser()" type="button" value="add"></input>
		</form>
	</div>
	<div id='modifyPane'>
		<div class="subtitle">
			modify user
		</div>
		<div id='usersTable'>			
		</div>
		<div id='modPanel'>
			<div class='editpane'>
				<button id='change'>Change</button>
			</div>
			<div class="mTableList" style="width:400px;">
			      <div class='pane'>
			      	<div class="paneTitle">View</div><div class='mViewList'></div>
			      </div>
			      <div class='space1'></div>
			      <div class='pane'>
			      	<div class="paneTitle">Edit</div><div class='mEditList'></div>
			      </div>
		  	</div>
		</div>
	</div>
	<div id='dbPane'>
		<div class="subtitle">
			databases
		</div>
		<div class='dbTable'>
		</div>
		<div class='dbstatus'>
		</div>
	</div>
</body>
<script src="js/control.js"></script>
</html>

<?php
	}
?>