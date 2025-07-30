# RasPi.vn 
#!/bin/bash
 
if pgrep python3 > /dev/null
then
echo "Stream video is running..."
/var/www/html/admin/stop_stream.sh
sudo /usr/bin/python /var/www/html/admin/take_photo.py
/var/www/html/admin/start_stream.sh
else
sudo /usr/bin/python /var/www/html/admin/take_photo.py
fi
