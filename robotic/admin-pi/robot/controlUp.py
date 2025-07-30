from flask import Flask, render_template_string, request   # Importing the Flask modules required for this project
import RPi.GPIO as GPIO     # Importing the GPIO library to control GPIO pins of Raspberry Pi
from time import sleep      # Import sleep module from time library to add delays
 
# Pins where we have connected servos
servo_pin = 23          
#servo_pin1 = 19
 
GPIO.setmode(GPIO.BCM)      # We are using the BCM pin numbering
# Declaring Servo Pins as output pins
GPIO.setup(servo_pin, GPIO.OUT)     
#GPIO.setup(servo_pin1, GPIO.OUT)
 
# Created PWM channels at 50Hz frequency
p = GPIO.PWM(servo_pin, 50)
#p1 = GPIO.PWM(servo_pin1, 50)
 
# Initial duty cycle
p.start(0)
#p1.start(0)
 
# Flask constructor takes the name of current module (__name__) as argument.
app = Flask(__name__)
# Enable debug mode
app.config['DEBUG'] = True
 
# Store HTML code
TPL = '''
<html>
    <head><title>Web Application to control Servos </title></head>
    <body>
    <h2> Web Application to Control Servos</h2>
        <form method="POST" action="test">
            <p>Slider 1 <input type="range" min="1" max="13" name="slider1" /> </p>
            <input type="submit" value="submit" />
        </form>
    </body>
</html>
'''
 
# which URL should call the associated function.
@app.route("/")
def home():
    return render_template_string(TPL)
 
@app.route("/test", methods=["POST"])
def test():
    # Get slider Values
    slider1 = request.form["slider1"]
    #slider = 13 - slider1
    #slider2 = request.form["slider2"]
    # Change duty cycle
    p.ChangeDutyCycle(13 - float(slider1))
    #print(13 - slider1)
    #p1.ChangeDutyCycle(float(slider2))
    # Give servo some time to move
    sleep(1)
    # Pause the servo
    p.ChangeDutyCycle(0)
    #p1.ChangeDutyCycle(0)
    return render_template_string(TPL)
 
# Run the app on the local development server
if __name__ == "__main__":
    app.run()
