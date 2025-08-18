<?php 
    session_start();   
    if($_SESSION['LoginInto'] == "TRUE") {
      $current = 'location';
        require_once("includes/header.php");
    }
    else {
         header('Location: /smartparking/login.php');
    }
?>
<div class="wrap" style="background: url(image/3.jpg);">
    <p style="text-align: center; color: white; font-size: 25px; font-weight: bold">Parking Location Monitoring</p>
  
        
   

        <div id="map" style="text-align: center;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3915.807998134491!2d106.66398021411855!3d11.053017357051363!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3174d1d7df763eaf%3A0xf4323e44f2867057!2sEastern+International+University!5e0!3m2!1sen!2s!4v1552401776866" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        </div>
</div>
<?php require_once("includes/footer.php"); ?> 
    <!-- Start of embedded google map -->

  <script>
   function initMap() {
       var myOptions = {
           zoom: 15,
           center: new google.maps.LatLng(21.0477359,105.7495967),
           mapTypeId: google.maps.MapTypeId.ROADMAP
       };
       map = new google.maps.Map(document.getElementById('map'), myOptions);
       marker = new google.maps.Marker({
           map: map,
           position: new google.maps.LatLng(21.0477359,105.7495967)
       });
       infowindow = new google.maps.InfoWindow({
           content: '<img src="<?php echo get_template_directory_uri() ?>/images/logo-vn4u.png" alt="" style="width:90px; "><div>Công ty Vn4U</div>'
       });
       google.maps.event.addListener(marker, 'click', function() {
           infowindow.open(map, marker);
       });
       infowindow.open(map, marker);
   }
   google.maps.event.addDomListener(window, 'load', initMap);
 
 
</script>
<script async defer
 src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDej-CHRaTCF5vaK9vkd8vty8Eo2Vv2Ids&callback=initMap&language=en">
</script>
<!-- end of embedded google map -->
<!-- 
    </div>
     <script src="js/jquery.js"></script>
     <script src="js/index.js"></script>
 -->