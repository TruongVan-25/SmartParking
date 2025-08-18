<!-- Show images in folder without paging - modified by skyblue -->
<?php
    session_start();   
    if($_SESSION['LoginInto'] == "TRUE") {
        $current = 'data';
        require_once("includes/header.php");        
    }
    else {
         header('Location: /smartparking/login.php');
    }
?>

<div class="wrap" style="background: url(image/3.jpg);">
    <div class="gallery">
        <?php         
        $per_page = 9;// Number of images per page, change for a different number of images per page

        // Get the page and offset value:
        if (isset($_GET['page'])) {
            $page = $_GET['page'] - 1;
            $offset = $page * $per_page;
        }
        else {
            $page = 0;
            $offset = 0;
        } 

        // counts how many files are in a certain directory.
        $thumbs = "media/video/";
				// $imgs = "video/videos/";

				

        $directory = "media/video/";
        $filetype = '*.*';    
        $files = glob($directory.$filetype);    

        $total_images = 0;
        //$files = glob($directory . "*");
        //$files = glob($directory . "*.{jpg,png,gif}",GLOB_BRACE);
        if ($files){
            $total_images = count($files);
        }
        // echo "There were $total_images files";
        // Calculate the number of pages:
        if ($total_images > $per_page) {//If there is more than one page
            $pages_total = ceil($total_images / $per_page);
            $page_up = $page + 2;
            $page_down = $page;
            $display ='';//leave the display variable empty so it doesn't hide anything
        } 
        else {//Else if there is only one page
            $pages = 1;
            $pages_total = 1;
            $display = ' class="display-none"';//class to hide page count and buttons if only one page
        } 

        ////// THEN WE DISPLAY THE PAGE COUNT AND BUTTONS:

        // echo '<h2'.$display.'>Page '; echo $page + 1 .' of '.$pages_total.'</h2>';//Page out of total pages

        $i = 1;//Set the $i counting variable to 1
        echo '<div id="pageNav"'.$display.'>';//our $display variable will do nothing if more than one page

        // Show the page buttons:
        if ($page) {
            echo '<a href="show_video.php"><button><<</button></a>';//Button for first page [<<]
            echo '<a href="show_video.php?page='.$page_down.'"><button><</button></a>';//Button for previous page [<]
        } 

        for ($i=1;$i<=$pages_total;$i++) {
            if(($i==$page+1)) {
            echo '<a href="show_video.php?page='.$i.'"><button class="active">'.$i.'</button></a>';//Button for active page, underlined using 'active' class
            }

            //In this next if statement, calculate how many buttons you'd like to show. You can remove to show only the active button and first, prev, next and last buttons:
            if(($i!=$page+1)&&($i<=$page+3)&&($i>=$page-1)) {//This is set for two below and two above the current page
            echo '<a href="show_video.php?page='.$i.'"><button>'.$i.'</button></a>'; }
        } 

        if (($page + 1) != $pages_total) {
        echo '<a href="show_video.php?page='.$page_up.'"><button>></button></a>';//Button for next page [>]
        echo '<a href="show_video.php?page='.$pages_total.'"><button>>></button></a>';//Button for last page [>>]
        }
        echo "</div>";// #pageNav end

        $max = $offset + $per_page;    
        if($max>$total_images){
            $max = $total_images;
        }
        
        // old code
        // Image extensions
        // $image_extensions = array("png","jpg","jpeg","gif");

        // Target directory
        $dir = 'media/video/';
        if (is_dir($dir)){
            
            if ($dh = opendir($dir)){
                $count = 1;
    // for($i = $offset; $i< $max; $i++){
                // Read files
                $index = $offset;
                for($index = $offset; $index< $max; $index++){
                    $file = $files[$index];
                    if($file != '' && $file != '.' && $file != '..'){
      
                        echo '<video style="padding: 5px;" width="33%" height="320" controls>';
						echo '<source src="'.$file.'" type="video/mp4" alt="" title="test video">';
						
						echo '<object data="'.$file.'" width="320" height="240">';
						echo '</object>';
						echo '</video>'; 
                        // echo "$file";
                           
                    }
                    
                }
                        
            }
            closedir($dh);
        }        
        ?>
    </div> <!-- end of gallery section -->
</div> <!-- end of wrap section -->

<!-- Script -->
<script type='text/javascript'>
$(document).ready(function(){

    // Intialize gallery
    var gallery = $('.gallery a').simpleLightbox();
});
</script>
<?php require_once("includes/footer.php"); ?>