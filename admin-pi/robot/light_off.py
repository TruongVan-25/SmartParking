import RPi.GPIO as GPIO
import time
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
GPIO.setup(19,GPIO.OUT)
GPIO.setup(26,GPIO.OUT)
GPIO.output(19,GPIO.LOW)
GPIO.output(26,GPIO.LOW)
GPIO.cleanup()
