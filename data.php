<?php 
    session_start();   
    if($_SESSION['LoginInto'] == "TRUE") {
      $current = 'data';
        require_once("includes/header.php");
        // include 'php/draw.php';
    }
    else {
         header('Location: /smartparking/login.php');
    }
?>

<div class="wrap" style="background: url(image/3.jpg);">
   <div class="row">  
      <div class="col-12" style="text-align: center;">
            <h2 class="Title" style="color: white"> REAL-TIME MONITORING DATA </h2>
            <button id="showDataTable" class="btn btn-success" style="font-size: 17px;">View Tabular Data</button>
            <button id="refresh_data" class="btn btn-info" style="font-size: 17px;">Refresh Data</button>         
      </div>
   </div>
   <div id="graph_data" class="row">
      <!-- <div id="Temperature"> -->
      <div class="col-sm-6" style="text-align: center; padding-left: 30px; padding-right: 30px">
         <h4 style="color: #e74c3c;">Temperature</h4>
         <div id="chart1" style="width: 100%; height: auto; padding: 0px"></div>
      </div>

      <div class="col-sm-6" style="text-align: center; padding-left: 30px; padding-right: 30px">
         <h4 style="color: #27ae60;">Humidity</h4>
         <div id="chart2" style="width: 100%; height: auto; padding: 0px"></div>
       </div>

      <div class="col-sm-6" style="text-align: center; padding-left: 30px; padding-right: 30px">
         <h4 style="color: #2980b9;">MQ2</h4>
         <div id="chart3" style="width: 100%; height: auto; padding: 0px"></div>
      </div>

      <div class="col-sm-6" style="text-align: center; padding-left: 30px; padding-right: 30px">
         <h4 style="color: #e67e22;">Distance to Obstacle</h4>
         <div id="chart4" style="width: 100%; height: auto; padding: 0px"></div> 
       </div>
      
       <?php include("./includes/graph_draw.php"); ?>
   </div>
    
</div> <!-- end of wrap -->
<script>
   
   document.getElementById('showDataTable').onclick = function () {
      window.location = 'displaying_data.php';
   }
   document.getElementById('refresh_data').onclick = function () {
      window.location.reload();
   }

</script>

<?php require_once("includes/footer.php"); ?>