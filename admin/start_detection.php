<?php
	
	// shell_exec('SCHTASKS /F /Create /TN _detection /TR "python E:\python\source\stream-video-browser\webstreaming.py --ip 0.0.0.0 --port 8000" /SC DAILY /RU INTERACTIVE');
	// shell_exec('SCHTASKS /RUN /TN "_detection"');
	// shell_exec('SCHTASKS /DELETE /TN "_detection" /F');
	exec('python start_detection.py');
?>