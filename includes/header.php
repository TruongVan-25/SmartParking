<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>AdminPage-MRS</title>
   
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
    
    <!-- <link href="https://fonts.googleapis.com/css?family=Cookie|Saira|Finger+Paint|Loved+by+the+King|Orbitron|Patrick+Hand|Wallpoet&display=swap" rel="stylesheet"> -->

   <!-- <link rel="stylesheet" href="js/morris.css"> -->
   <script src="js/jquery.min.js"></script>
   <!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
   <script src="js/raphael.min.js"></script>
   <script src="js/morris.min.js"></script>

   
   <link rel="stylesheet" href="css/bootstrap.css">
    
    <!-- add new for photo show -->
    <!-- <link href='photo/simplelightbox-master/dist/simplelightbox.min.css' rel='stylesheet' type='text/css'> -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/morris.css">
    <!-- for photo gallery -->
    <link href='css/simplelightbox.min.css' rel='stylesheet' type='text/css'>
    <!-- <link rel="stylesheet" href="photo/gallery.css"> -->
    <!-- <script src='photo/jquery-3.0.0.js' type='text/javascript'></script>  -->
    <script type="text/javascript" src="js/simple-lightbox.js"></script>
</head>
<body>
    <div class="wrap" >
        <div class="row" style="width: 100%; margin-left: auto; margin-right: auto">
            <div class="col-3" style="text-align: center;">
                <img class="logo" src="image/eiulogo1.png">
            </div>
            <div class="col-9" style="text-align: center;">
                <div class="Title">Smart Parking System for Smart Environments</div>
            </div>
        </div>  
        <div class="example2">
            <h3> This work is handled by Admin team @CSC at EIU - &copy 2025-2026 All Rights Reserved  </h3>
        
        </div><!-- End row -->    
        
        <div class="row" style="width: 100%; margin-left: auto; margin-right: auto">
            <div id="nav">                
                <ul>
                    <li <?php if($current == 'home') {echo 'class="current"';} ?>><a href="/smartparking/index.php" style="font-family: sans-serif;font-weight: bold;">Home</a></li>
                    <li <?php if($current == 'control') {echo 'class="current"';} ?>><a href="/smartparking/control.php" style="font-family: sans-serif;font-weight: bold;" id="activeControl"> Slot Control</a></li>
                    <li <?php if($current == 'data') {echo 'class="current"';} ?>><a href="#" style="font-family: sans-serif;font-weight: bold;">Data</a>
                        <ul class="sub-menu">
                            <li><a href="/smartparking/data.php" style="font-family: 'Saira', sans-serif;font-weight: bold;">Data</a></li>
                            <li><a href="/smartparking/show_photo.php" style="font-family: 'Saira', sans-serif;font-weight: bold;">Image</a></li>
                            <li><a href="/smartparking/show_video.php" style="font-family: 'Saira', sans-serif;font-weight: bold;">Video</a></li>
                        </ul>
                    </li>
                    <li <?php if($current == 'location') {echo 'class="current"';} ?>><a href="/smartparking/location.php" style="font-family: sans-serif;font-weight: bold;">Location</a></li>
                    <li><a href="/smartparking/index.php?logout='1'" style="font-family: sans-serif;font-weight: bold;">Logout</a></li>
                </ul>            
            </div>
        </div>
    </div> <!-- End row -->