<?php  

include("connectSQL.php");


//export.php  

$output = '';
if(isset($_POST["export"]))
{
 $query = "SELECT * FROM monitor";
 $result = mysqli_query($conn, $query);
 if(mysqli_num_rows($result) > 0)
 {
  $output .= '
   <table class="table" bordered="1">  
                    <tr>  
                         <th>DATE</th>  
                         <th>TEMPERATURE</th>  
                         <th>HUMINITY</th>  
                         <th>WATER LEVEL</th>
                         <th>PPM</th>
                    </tr>
  ';
  while($row = mysqli_fetch_array($result))
  {
   $output .= '
    <tr>  
                         <td>'.$row["date"].'</td>  
                         <td>'.$row["temperature"].'</td>  
                         <td>'.$row["humidity"].'</td>  
                         <td>'.$row["waterlevel"].'</td>  
                         <td>'.$row["ppm"].'</td>
    </tr>
   ';
  }
  $output .= '</table>';
  header('Content-Type: application/xls');
  header('Content-Disposition: attachment; filename=Data-Table.xls');
  header("Pragma: no-cache"); 
  header("Expires: 0");
  echo $output;
 }
}
?>