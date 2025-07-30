import Adafruit_DHT
import RPi.GPIO as GPIO
import time
import urllib
import urllib2
import threading
import Adafruit_ADS1x15

# Adafruit_DHT ho tro nhieu loai cam bien DHT, o day dung DHT11 nen chon cam bien  DHT11
chon_cam_bien = Adafruit_DHT.DHT11

# Create an ADS1115 ADC (16-bit) instance.
adc = Adafruit_ADS1x15.ADS1115()
GAIN = 1

#Set mode
GPIO.setmode(GPIO.BCM)

# Humidity and Temperature
pin_sensor = 25
GPIO.setwarnings(False)

#Motion Light (Garden Light)
motion_pin = 17
gar_pin = 27
buzzer_pin = 9
GPIO.setup(buzzer_pin, GPIO.OUT)
GPIO.output(buzzer_pin, GPIO.LOW)  
GPIO.setup(motion_pin, GPIO.IN)
GPIO.setup(gar_pin, GPIO.OUT)
GPIO.output(gar_pin, GPIO.LOW)  

#bedroom
bed_pin = 11
GPIO.setup(bed_pin,GPIO.OUT)

#kitchen
kit_pin = 13
GPIO.setup(kit_pin,GPIO.OUT)

#bathroom
bath_pin = 19
GPIO.setup(bath_pin,GPIO.OUT)

#table
tab_pin = 26
GPIO.setup(tab_pin,GPIO.OUT)

while(1):
	#Temperature and Humidity
	hum1, temp1 = Adafruit_DHT.read_retry(chon_cam_bien, pin_sensor)
	
	# Kiem tra gia tri tra ve tu cam bien (do _am va nhiet_do) khac NULL
	if hum1 is not None and temp1 is not None:
		print ("Temperature = {0:0.1f}  Humidity = {1:0.1f}\n").format(temp1,hum1)
		time.sleep(2)
		print (temp1)
		print (hum1)
	else:
		# Loi :(
		print("Error :\n")
	
	#Motion Light
	state = GPIO.input(motion_pin)
	if state == 1: 
		print ("Intruder detected")
		GPIO.output(gar_pin, GPIO.HIGH)
		mov1 = 1
		gar1 = 1
		time.sleep(2)
	else:
		GPIO.output(gar_pin, GPIO.LOW)
		print ("No intruders")
		mov1 = 0
		gar1 = 0
		time.sleep(2)
	
	#Gas
	# Read all the ADC channel values in a list.
	values = [0]*4
	for i in range(1):
		# Read the specified ADC channel using the previously set gain value.
		values[i] = adc.read_adc(i, gain=GAIN)
	# Print the ADC values.
	print('{0:>6}'.format(*values))
	# Pause for half a second.
	time.sleep(0.5)

	if values[0] > 4000:
		GPIO.output(buzzer_pin, GPIO.HIGH)
		print ("Gas is Leaks")
		gas1 = 1
	else:
		GPIO.output(buzzer_pin, GPIO.LOW)
		print ("Normal")
		gas1 =0
        
        
	#Bedroom
	if GPIO.input(bed_pin) == 1:
		print("Bedroom Light Is On")
		bed1 = 1
	else:
		print("Bedroom Light Is Off")
		bed1 = 0
	
	#Kitchen
	if GPIO.input(kit_pin) == 1:
		print("Kitchen Light Is On")
		kit1 = 1
	else:
		print("Kitchen Light Is Off")
		kit1 = 0
	
	#Bathroom
	if GPIO.input(bath_pin) == 1:
		print("Bathroom Light Is On")
		bat1 = 1
	else:
		print("Bathroom Light Is Off")
		bat1 = 0
	
	#Table Light
	if GPIO.input(tab_pin) == 1:
		print("Table Light Is On")
		tab1 = 1
	else:
		print("Table Light Is Off")
		tab1 = 0
	
	#Send data to web
	url = 'http://192.168.1.222/php_app/includes/addnew.php'
	values = {'temp1': temp1, 'hum1' : hum1, 'mov1' : mov1, 'gas1' : gas1, 'gar1' : gar1, 'bed1' : bed1,'kit1' : kit1, 'bat1' : bat1, 'tab1' : tab1}
	
	#encode the values from the dictionary.
	data = urllib.urlencode(values)
	#combine the values and the url.
	req = urllib2.Request(url, data)
	#send the url open request and recieve the response.
	response = urllib2.urlopen(req)
