<?php
session_start();
if ($_SESSION['LoginInto'] == "TRUE") {
   $current = 'data';
   require_once("includes/header.php");
} else {
   header('Location: /smartparking/login.php');
}
?>

<div class="wrap" style="background: url(image/3.jpg);">
   <div class="row">
      <div class="col-12" style="text-align: center;">
         <h2 class="Title" style="color: white">PARKING DATA OVERVIEW</h2>
         <button id="refresh_data" class="btn btn-info" style="font-size: 17px;">Refresh Data</button>
      </div>
   </div>

   <div class="row" style="padding: 20px;">
      <?php include("./includes/graph_draw.php"); ?>
   </div>
</div>

<script>
   document.getElementById('refresh_data').onclick = function () {
      window.location.reload();
   }
</script>

<?php require_once("includes/footer.php"); ?>