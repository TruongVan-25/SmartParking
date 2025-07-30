# RasPi.vn 
#!/bin/bash
 
if pgrep python3 > /dev/null
then
echo "Stream video is running..."
sudo /usr/bin/python3 /var/www/html/smart_home/admin/face/FacialRecognitionProject/03_face_recognition.py
else 
echo "Stream video is stop, start stream first..."
/var/www/html/smart_home/admin/stream/start_stream.sh
sudo /usr/bin/python3 /var/www/html/smart_home/admin/face/FacialRecognitionProject/03_face_recognition.py
fi
