<?php
session_start();
if(!isset($_SESSION['UID']))
	header("location: index.php");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Excel Upload</title>
	<link rel="stylesheet" type="text/css" href="css/upload.css">
	<link href='https://fonts.googleapis.com/css?family=Quattrocento+Sans' rel='stylesheet' type='text/css'>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.full.min.js"></script>
</head>
<body>
	<div class='header'>
		<img id='logo' src="images/logo.png">
		<img id='dsm_logo' src="images/dsm_logo.png">
	</div>
	<div class="button-pane">
		<button class="button" onclick="location.href='view.php';">go back</button>
		<button class="button" onclick="location.href='logout.php';">logout</button><br>
	</div>
	<div class='space2'></div>
	<div class='title'>
		excel file upload
	</div>
	<div class="main">	
		<div class="container">
			<div class='instr'>Select a file</div>
			<form>
				<input type="file" name="xlfile" id="xlf" style="margin-bottom: 20px;" />
			</form>
			<div class='instr'>Grant users rights</div>
	  		<div class="TableList" style="width:400px;">
		      <div class='pane'>
		      	<div class="paneTitle">View</div><div class='viewList'></div>
		      </div>
		      <div class='space1'></div>
		      <div class='pane'>
		      	<div class="paneTitle">Edit</div><div class='editList'></div>
		      </div>
	  		</div>
	  		<input id='uploadBtn' type="button" onclick="initUpload()" value="upload">
	  		<div id='status' style="display:inline-block"></div>
		</div>
		<div class="space"></div>
		<div class="container">
			<h3 id='subtitle'>Points to remember</h3>
			<ul id='list'>
			<li class='point'>The file must be of extension .xlsx only.</li>
			<li class='point'>The name of the database should be the name of the file. Do not use special characters in the file name.</li>
			<li class='point'>The first row in the excel document must contain the name of the columns only.</li>
			<li class='point'>Do not merge columns or leave them blank. Maintain a proper grid.</li>
			<li class='point'>Select the users you wish to grant view and edit rights respectively.</li>
			</ul>
		</div>
	</div>
<script src="js/upload.js"></script>
</body>
</html>