<?php
	//khai bao bien de ket noi
	$db_host = 'localhost'; // Server Name
	$db_user = 'smartparking'; // Username
	$db_pass = 'cyber@2025'; // Password
	$db_name = 'smart_parking'; // Database Name
	
	//ket noi mysql
	$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>