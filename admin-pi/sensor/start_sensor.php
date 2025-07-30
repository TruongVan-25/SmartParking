<?php
	exec('sudo /usr/bin/python /var/www/html/admin/sensor/send-temperature.py');
	exec('sudo /usr/bin/python /var/www/html/admin/sensor/send-mq2.py');
	exec('sudo /usr/bin/python /var/www/html/admin/sensor/send-distance.py');
?>
