<?php

$test="false";

if(isset($_POST['RegisterInto'])){



	include("connectSQL.php");


	$EmailRegister = $_POST["EmailRegister"];
	$PasswordRegister = $_POST["PasswordRegister"];
	$PasswordRegisterConfirm = $_POST["PasswordRegisterConfirm"];
	$Name = $_POST["Name"];
	$DateOfBirth = $_POST["DateOfBirth"];
	$Address = $_POST["Address"];

	if( $PasswordRegister=='' || $PasswordRegisterConfirm==''
	 || !preg_match("/^[a-zA-Z ]*$/",$Name) || $DateOfBirth=='' || $Address=='' || $PasswordRegister!=$PasswordRegisterConfirm
	 || strlen($PasswordRegister) <=4 || strlen($Address) <=4 || !filter_var($EmailRegister, FILTER_VALIDATE_EMAIL)) {
		$test="false";
		$conn->close();
	}
	else {
		$result = mysqli_query($conn,"INSERT INTO information (Email,Password,Name,DateOfBirth,Address) VALUES ('$EmailRegister','$PasswordRegister','$Name','$DateOfBirth','$Address')");
		$test="true";
		$conn->close();
	}
} 

if($test=="true"){
	header('Location: /robotic/login.php');
}
else {
	echo "Register unsuccessfully";
}
if(isset($_POST['Cancel'])) {
	header('Location: /robotic/login.php');
}

?>