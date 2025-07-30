#!/bin/bash
 
if pgrep python3 > /dev/null
then
echo "Stream video is running..."
else
/usr/bin/python3 /var/www/html/admin/start_stream_video.py > /dev/null 2>&1&
echo "Start streaming at port 8000..."
fi
