# RasPi.vn 
#!/bin/bash
 
if pgrep python > /dev/null
then
echo "Stream video is running..."
/var/www/html/smart_home/admin/stream/stop_stream.sh
sudo /usr/bin/python /var/www/html/smart_home/admin/stream/take_photo.py
/var/www/html/smart_home/admin/stream/start_stream.sh
else
sudo /usr/bin/python /var/www/html/smart_home/admin/stream/take_photo.py
fi
