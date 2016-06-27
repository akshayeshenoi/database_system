<?php

session_start();

if(!isset($_SESSION['UID']))

	header("location: index.php");

?>



<!DOCTYPE html>

<html>

<head>

	<title>View Database</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/s/dt/jszip-2.5.0,dt-1.10.10,b-1.1.0,b-html5-1.1.0,se-1.1.0/datatables.min.css"/>

	<script type="text/javascript" src="https://cdn.datatables.net/s/dt/jszip-2.5.0,dt-1.10.10,b-1.1.0,b-html5-1.1.0,se-1.1.0/datatables.min.js"></script>

	<script type="text/javascript" src="js/view.js"></script>

	<link rel="stylesheet" type="text/css" href="css/view.css">

</head>

<body>

	<div class='header'>

		<img id='logo' src="images/logo.png">

		<img id='dsm_logo' src="images/dsm_logo.png">

	</div>

	<div class='options'>

		<div class="left">

			<select id='selectOption' onchange='selectDB()'><option selected disabled>Select a database</option></select>

			<div class="dbstatus" style="display:inline-block"></div>

		</div>

		<div class="right">

			<span class="user"></span>

			<div class="button-pane">

				<button class="button" onclick="location.href='upload.php';">upload</button>

				<button class="button" onclick="location.href='logout.php';">logout</button>

				<button id='adminBtn' class="button" onclick="location.href='control.php';">admin</button>

			</div>

		</div>

		<br id='clear'>

	</div>	

	<div id='result'>

	</div>

	<div class='editpane'>

	</div>

</body>

</html>

