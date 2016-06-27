<?php
session_start();
$info=array('UID'=>$_SESSION["UID"],'name'=>$_SESSION["name"],'admin'=>$_SESSION['admin']);
echo json_encode($info);