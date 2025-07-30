<?php
	
	if(isset($_GET['angleX'])){
	
	  	$angleX=$_GET['angleX'];
	  	$angleY=$_GET['angleY'];
	  	// $temp1 = 22; $hum1 = 77;
	  	echo "get data: angleX =  $angleX,  angleY = $angleY";

	
	//echo "get data: angleX =  $angleX,  angleY = $angleY";
	$output = shell_exec("sudo /usr/bin/python /var/www/html/move/camera_left.py $angleY $angleX");
	echo "<pre>$output</pre>";
	//exec('sudo /usr/bin/python /var/www/html/move/camera_left.py' + .$angleY + .$angleX);
	//exec('sudo /usr/bin/python /var/www/html/move/camera_left.py 90 50');
	//exec('sudo /usr/bin/python /var/www/html/move/camera_left.py');
	//exec('sudo /var/www/html/move/trai');
	}
	exec('sudo /usr/bin/python /var/www/html/move/camera_left.py');
?>
