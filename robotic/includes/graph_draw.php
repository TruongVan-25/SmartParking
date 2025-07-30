<?php 
   include("./php/connectSQL.php");

   //get temperature and humidity data
   $limit_data = 20;
   $query = "(SELECT * FROM `monitor` ORDER BY `date` DESC LIMIT $limit_data) ORDER BY `date` ASC";
   $result = mysqli_query($conn, $query);
   $chart_data = '';
   while($row = mysqli_fetch_array($result))
   {
    $chart_data .= "{date:'".$row["date"]."', humidity:".$row["humidity"].", temperature:".$row["temperature"]."}, ";
   }
   $chart_data = substr($chart_data, 0, -2);
  
   // get mq2 data
   $query = "(SELECT * FROM `mq2sensor` ORDER BY `date` DESC LIMIT $limit_data) ORDER BY `date` ASC";
   $result = mysqli_query($conn, $query);
   $chart_data_mq2 = '';
   while($row = mysqli_fetch_array($result))
   {
      $chart_data_mq2 .= "{date:'".$row["date"]."', mq2:".$row["mq2"]."}, ";
   }
   $chart_data_mq2 = substr($chart_data_mq2, 0, -2);

   // get distance data
   $query = "(SELECT * FROM `distance` ORDER BY `date` DESC LIMIT $limit_data) ORDER BY `date` ASC";
   $result = mysqli_query($conn, $query);
   $chart_data_distance = '';
   while($row = mysqli_fetch_array($result))
   {
      $chart_data_distance .= "{date:'".$row["date"]."', distance:".$row["distance"]."}, ";
   }
   $chart_data_distance = substr($chart_data_distance, 0, -2);
?>

<script>
 Morris.Line({
   element : 'chart1',
   data:[<?php echo $chart_data; ?>],
   xkey:'date',
   ykeys:['temperature'],
   labels:['temperature'],
   hideHover:'auto',
   lineColors: ['#e74c3c'],
   stacked:true
 });

</script>


<script>
  Morris.Line({
   element : 'chart2',
   data:[<?php echo $chart_data; ?>],
   xkey:'date',
   ykeys:['humidity'],
   labels:['humidity'],
   hideHover:'auto',
   lineColors: ['#27ae60'],
   stacked:true
 });
</script>
<script>
  Morris.Line({
   element : 'chart3',
   data:[<?php echo $chart_data_mq2; ?>],
   xkey:'date',
   ykeys:['mq2'],
   labels:['mq2'],
   hideHover:'auto',
   lineColors:['#2980b9'],
   stacked:true
 });
</script>

<script>
  Morris.Line({
   element : 'chart4',
   data:[<?php echo $chart_data_distance; ?>],
   xkey:'date',
   ykeys:['distance'],
   labels:['distance'],
   hideHover:'auto',
   lineColors: ['#e67e22'],
   stacked:true
   // gridTextColor: ['#e67e22']
 });
  </script>