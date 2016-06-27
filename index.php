<?php
session_start();
if(isset($_SESSION["name"])){
	//header("location:view.php");
}
?>

<!DOCTYPE html>
<html>
<head> 
<title>IAESTE Database System</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/login.css">
<body>
<form class="form">
	<div class='logo'><img src="images/logo.jpg"></div>
	<div class='dsm_logo'><img src="images/dsm_logo.png"></div>
	<input type='text' id ='username' placeholder='username'><br>
	<input type='password' id ='password' placeholder='password'><br>
	<input id="submit" onclick="login()" type="button" value="login"></form>
</form>
<script type="text/javascript">
	function login () {		
		//alert("here?");	
		var username=document.getElementById('username').value;
		var password=document.getElementById('password').value;
		var jdata={"username":username,"password":password};
		jdata = JSON.stringify(jdata);
		
		$.ajax({
		   url: 'login/login.php',
		   type: 'POST',
		   data: jdata,
		   contentType:'application/json',

		   success: function(result){
		     //On ajax success do this
		     var data=JSON.parse(result);
		     if(data.status=="fail")
		     	alert("wrong username,password");
		     if(data.status=="success")
		     	window.location="view.php";
		     
		    },

		   error: function(xhr, ajaxOptions, thrownError) {
		      //On error do this
		        if (xhr.status == 200) {

		            alert(ajaxOptions);
		        }
		        else {
		            alert(xhr.status);
		            alert(thrownError);
		        }
		    }
		});
	}
</script>
</body>
</html>
