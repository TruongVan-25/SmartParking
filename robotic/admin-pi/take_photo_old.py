import datetime
import time
from time import sleep
from picamera import PiCamera

camera = PiCamera()
camera.resolution = (1024, 768)
camera.rotation = 180
camera.start_preview()

date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")
camera.capture("/var/www/html/controlcar/photo/images/photo_"+ date + ".jpg")
sleep(1)
camera.resolution = (300, 200)
camera.capture("/var/www/html/controlcar/photo/images/thumbnail/photo_"+ date + ".jpg")

print("Take photo done!")
