<?php
	
	shell_exec('SCHTASKS /F /Create /TN _detection /TR "python E:\webXAMPP\robotic\admin\detection.py --prototxt E:\webXAMPP\robotic\admin\MobileNetSSD_deploy.prototxt --model E:\webXAMPP\robotic\admin\MobileNetSSD_deploy.caffemodel" /SC DAILY /RU INTERACTIVE');
	shell_exec('SCHTASKS /RUN /TN "_detection"');
	shell_exec('SCHTASKS /DELETE /TN "_detection" /F');
?>