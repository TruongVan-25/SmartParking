from time import sleep
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)
pan = 18
tilt = 23
angleX = 0
angleY = 0
angleX_new = 0
angleY_new = 0

GPIO.setup(tilt, GPIO.OUT) # white => TILT
GPIO.setup(pan, GPIO.OUT) # gray ==> PAN
def setServoAngle(servo, angle):
	assert angle >=30 and angle <= 150
	pwm = GPIO.PWM(servo, 50)
	pwm.start(8)
	dutyCycle = angle / 18. + 3.
	pwm.ChangeDutyCycle(dutyCycle)
	sleep(0.3)
	pwm.stop()
if __name__ == '__main__':
	import sys
	allNums = []
	with open(r"/var/www/html/move/camera_angle.txt", "r+") as f:
		data = f.readlines() # read the text file
		for line in data:
			allNums += line.strip().split(" ") 
		print(allNums)
		angleX = allNums[0]
		angleY = allNums[1]
		if int(angleX) > 39:
			angleX_new = int(angleX) - 10
			print(angleX_new)
			print(angleY)
			evenNumber = open('/var/www/html/move/camera_angle.txt', 'w')  #writes even numbers into a file
			evenNumber.write(str(angleX_new) + ' ')
			evenNumber.write(str(angleY) + '\n')
			setServoAngle(tilt, angleX_new)
	GPIO.cleanup()
	
