import RPi.GPIO as GPIO
import time
import sys
import cv2
import numpy as np
import os
import urllib
import urllib.request
from urllib.request import urlopen

# Pushbullet
from pushbullet import Pushbullet
from time import sleep
pb = Pushbullet("o.uUnXjCFv6IaZeFdGlCFkQmV7GPCvmcbC")
print(pb.devices)

# Motor
# mode=GPIO.getmode()

# GPIO.cleanup()

Forward = 20
Backward = 13
sleeptime = 1
en = 21
temp1 = 1

GPIO.setmode(GPIO.BCM)
GPIO.setup(Forward, GPIO.OUT)
GPIO.setup(Backward, GPIO.OUT)
GPIO.setup(en, GPIO.OUT)
GPIO.output(Forward, GPIO.LOW)
GPIO.output(Backward, GPIO.LOW)
p = GPIO.PWM(en, 1000)

p.start(25)
print("Control the speed and direction of motor - door lock .....")


def forward(x):
    GPIO.output(Forward, GPIO.HIGH)
    print("Moving to the Left side")
    time.sleep(x)
    GPIO.output(Forward, GPIO.LOW)


def reverse(x):
    GPIO.output(Backward, GPIO.HIGH)
    print("Moving to the Right side")
    time.sleep(x)
    GPIO.output(Backward, GPIO.LOW)


def stop(x):
    GPIO.output(Forward, GPIO.LOW)
    GPIO.output(Backward, GPIO.LOW)
    print("Stop Moving... ")
    time.sleep(x)


# Face
recognizer = cv2.face.LBPHFaceRecognizer_create()
recognizer.read('trainer/trainer.yml')
cascadePath = "/home/pi/Test/face/Cascades/haarcascade_frontalface_default.xml"
faceCascade = cv2.CascadeClassifier(cascadePath)
font = cv2.FONT_HERSHEY_SIMPLEX
# iniciate id counter
id = 0
# names related to ids: example ==> Tien: id=1,  etc
names = ['None', 'Hang', 'Tien', 'Duc', 'Vinh', 'Phu']
# Initialize and start realtime video capture
cam = cv2.VideoCapture("http://192.168.1.222:8008/stream.mjpg")
#cam = cv2.VideoCapture(0)
cam.set(3, 640)  # set video widht
cam.set(4, 480)  # set video height
# Define min window size to be recognized as a face
minW = 0.1*cam.get(3)
minH = 0.1*cam.get(4)
while True:
    ret, img = cam.read()
    # img = cv2.flip(img, 1) # Flip vertically
    gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

    faces = faceCascade.detectMultiScale(
        gray,
        scaleFactor=1.2,
        minNeighbors=5,
        minSize=(int(minW), int(minH)),
    )
    for(x, y, w, h) in faces:
        cv2.rectangle(img, (x, y), (x+w, y+h), (0, 255, 0), 2)
        id, confidence = recognizer.predict(gray[y:y+h, x:x+w])
        # Check if confidence is less them 100 ==> "0" is perfect match
        if (confidence < 85):
            id = names[id]
            confidence = "  {0}%".format(round(100 - confidence))
            
             
            p.ChangeDutyCycle(35)
            reverse(1)
            stop(5)
            forward(1)
            GPIO.cleanup()

        else:
            id = "unknown"
            #ID = 0
            #dev = pb.get_device('Kan')
            #device = pb.devices[0]
            #push = pb.push_sms(device, "+869803404","Someone try to enter your house")

            confidence = "  {0}%".format(round(100 - confidence))

        cv2.putText(img, str(id), (x+5, y-5), font, 1, (255, 255, 255), 2)
        cv2.putText(img, str(confidence), (x+5, y+h-5),
                    font, 1, (255, 255, 0), 1)
        # Send Data
        #url = 'http://192.168.1.222/smart_home/includes/addface.php'
        #values = {'Name': Name, 'ID': ID}
        #data = urllib.urlencode(values)    #encode the values from the dictionary.
        #req = urllib2.Request(url, data)    #combine the values and the url.
        #response = urllib2.urlopen(req)   #send the url open request and recieve the response.
    
    cv2.imshow('camera', img)
    k = cv2.waitKey(10) & 0xff  # Press 'ESC' for exiting video

    if k == 27:
        break
# Do a bit of cleanup
print("\n [INFO] Exiting Program and cleanup stuff")
cam.release()
cv2.destroyAllWindows()
