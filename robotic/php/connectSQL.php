<?php
	//khai bao bien de ket noi
	$db_host = 'localhost'; // Server Name
	$db_user = 'root'; // Username
	$db_pass = ''; // Password
	$db_name = 'robotic'; // Database Name
	//ket noi mysql
	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>