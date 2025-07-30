import Adafruit_DHT
import RPi.GPIO as GPIO
import time
import urllib
import urllib2
import threading


# Adafruit_DHT ho tro nhieu loai cam bien DHT, o day dung DHT11 nen chon cam bien  DHT11
chon_cam_bien = Adafruit_DHT.DHT22

GPIO.setmode(GPIO.BCM)
# chan DATA duoc noi vao chan GPIO25 cua PI
pin_sensor = 25

print ("cam bien do am DHT 11")
global temperature
global humidity
global temp1
global hum1


while(1):

   hum1, temp1 = Adafruit_DHT.read_retry(chon_cam_bien, pin_sensor)
   
   # Kiem tra gia tri tra ve tu cam bien (do _am va nhiet_do) khac NULL
   if hum1 is not None and temp1 is not None && hum1 < 100 && temp1 < 100:
      print ("Temperature = {0:0.1f}  Humidity = {1:0.1f}\n").format(temp1,hum1)
      url = 'http://172.16.10.175/robotic/includes/addnew.php'
      values = {'temp' : temp1, 'hum' : hum1}
      data = urllib.urlencode(values)    #encode the values from the dictionary.
      req = urllib2.Request(url, data)    #combine the values and the url.
      response = urllib2.urlopen(req)   #send the url open request and recieve the response.
      time.sleep(7)
   else:
      print("Error :\n")


