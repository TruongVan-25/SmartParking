<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Mobile smartparking System - LoginPage</title>

    <link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
    <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body class="bg">

  <div class="wrap" >
      <br><br><br><br><br><br>
      <div class="row">
          <span href="#" class="buttonLogin" style="width: 300px">Mobile smartparking System</span>
      </div>

      <div class="row">
          <div id="login">
              <div id="triangle"></div>
              <h1>System Administration</h1>
              <form method="POST" action="php/checkUser.php">
                <input type="email" name ="Email" placeholder="Email" />
                <input type="password" name="Password" placeholder="Password" />
                <input type="submit" name="Login" value="Login" /> 
                <input type="submit" name="Register" value="Register">
              </form>
          </div>
      </div>

        
  </div>
  <!-- <script src='http://codepen.io/assets/libs/fullpage/jquery.js'></script> -->
  <!-- <script src="js/index.js"></script> -->
  <!-- <script src="php/conn2.php"></script> -->
</body>

</html>