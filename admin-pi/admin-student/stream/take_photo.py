import datetime
import time
from time import sleep
from picamera import PiCamera

camera = PiCamera()

date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")
camera.resolution = (1024, 768)
camera.capture("/var/www/html/smart_home/photo/images/photo_"+ date + ".jpg")

camera.resolution = (300, 200)
camera.capture("/var/www/html/smart_home/photo/images/thumbnail/photo_"+ date + ".jpg")

print("Take photo done!")
