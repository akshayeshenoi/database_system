<?php
	session_start();
	require '../conn.php';

	$file_name = $_FILES['xlfile']['name'];
    $file_tmp = $_FILES['xlfile']['tmp_name'];

    move_uploaded_file($file_tmp,"../fuploads/".$file_name);
    echo 'Uploaded raw file';
?>