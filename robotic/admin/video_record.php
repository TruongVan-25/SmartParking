<?php
	//exec('sudo /var/www/html/admin/start_stream.sh');
	exec('python E:\webXAMPP\robotic\admin\video-record.py');
	// shell_exec('SCHTASKS /F /Create /TN _record /TR "python E:\webXAMPP\robotic\admin\video-record.py" /SC DAILY /RU INTERACTIVE');
	// shell_exec('SCHTASKS /RUN /TN "_record"');
	// shell_exec('SCHTASKS /DELETE /TN "_record" /F');
	
?>
