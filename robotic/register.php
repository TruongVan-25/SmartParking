<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
	<title>Register</title>
	 <link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
   <!-- <link rel="stylesheet" type="text/css" href="css-bootstrap/bootstrap.css"> -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="bg">
  <div class="wrap">
  	<br><br><br>
    <div class="row">
        <div id="login">
              <div id="triangle"></div>
              <h1>Register</h1>
              <form method="POST" action="php/addUser.php">
                <input type="email" name ="EmailRegister" placeholder="Email" />
                <input type="password" name="PasswordRegister" placeholder="Password" />
                <input type="password" name="PasswordRegisterConfirm" placeholder="Confirm Password" />
                <input type="text" name="Name" placeholder="Your Name" />
                <input type="date" name="DateOfBirth" placeholder="Date of Birth" />
                <input type="text" name="Address" placeholder="Your Address " />
                <input type="submit" name="RegisterInto" value="Register"/>
                <input type="submit" name="Cancel" value="Cancel"/>
              </form>
          </div>
    </div>
  </div>
	
   <!-- <script src="php/connUP.php"></script> -->
</body>
</html>