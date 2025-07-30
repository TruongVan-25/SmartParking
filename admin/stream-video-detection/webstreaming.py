# USAGE
# python webstreaming.py --ip 0.0.0.0 --port 8000

# import the necessary packages
from pyimagesearch.motion_detection import SingleMotionDetector
from pyimagesearch.human_detection import HumanDetector
from imutils.video import VideoStream
from flask import Response
#from flask import Flask
from flask import render_template
from flask import Flask, jsonify, request
import threading
import argparse
import datetime
import imutils
import time
import cv2
from sendMessage import *
import json, os, signal

# initialize the output frame and a lock used to ensure thread-safe
# exchanges of the output frames (useful for multiple browsers/tabs
# are viewing tthe stream)
outputFrame = None
lock = threading.Lock()

# initialize a flask object
app = Flask(__name__)

# initialize the video stream and allow the camera sensor to
# warmup
#vs = VideoStream(usePiCamera=1).start()
vs = VideoStream(src="http://192.168.1.211:8000/stream.mjpg").start()
#vs = VideoStream(src=0).start()
#vs = cv2.VideoCapture("http://192.168.1.112:8000/stream.mjpg")
#if not (vs.isOpened()):
#	print("[INFO] Can not open video device...")
time.sleep(2.0)

@app.route("/")
def index():
	# return the rendered template
	return render_template("index.html")

def detect_motion(frameCount):
	# grab global references to the video stream, output frame, and
	# lock variables
	global vs, outputFrame, lock

	# initialize the motion detector and the total number of frames
	# read thus far
	md = SingleMotionDetector(accumWeight=0.1)
	total = 0
	
	# loop over frames from the video stream
	while True:
		# read the next frame from the video stream, resize it,
		# convert the frame to grayscale, and blur it
		frame = vs.read()
		frame = imutils.resize(frame, width=400)
		gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
		gray = cv2.GaussianBlur(gray, (7, 7), 0)

		# grab the current timestamp and draw it on the frame
		timestamp = datetime.datetime.now()
		cv2.putText(frame, timestamp.strftime(
			"%A %d %B %Y %I:%M:%S%p"), (10, frame.shape[0] - 10),
			cv2.FONT_HERSHEY_SIMPLEX, 0.35, (0, 0, 255), 1)
		#cv2.putText(frame, "[INFO] we found {} human".format(human_number), (50,50), cv2.FONT_HERSHEY_DUPLEX, 0.8,(0,0,255))
		# if the total number of frames has reached a sufficient
		# number to construct a reasonable background model, then
		# continue to process the frame
		if total > frameCount:
			# detect motion in the image
			motion = md.detect(gray)

			# cehck to see if motion was found in the frame
			if motion is not None:
				# unpack the tuple and draw the box surrounding the
				# "motion area" on the output frame
				(thresh, (minX, minY, maxX, maxY)) = motion
				cv2.rectangle(frame, (minX, minY), (maxX, maxY),
					(0, 0, 255), 2)
		
		# update the background model and increment the total number
		# of frames read thus far
		md.update(gray)
		total += 1

		# acquire the lock, set the output frame, and release the
		# lock
		with lock:
			outputFrame = frame.copy()
			
def detect_human(frameCount):
	# grab global references to the video stream, output frame, and
	# lock variables
	global vs, outputFrame, lock

	# initialize the motion detector and the total number of frames
	# read thus far
	hd = HumanDetector(accumWeight=0.1)
	total = 0
	human_number = 0
	key = cv2.waitKey(30) & 0xFF
	send_message_flag = None
	date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")
	# loop over frames from the video stream
	while True:
		# read the next frame from the video stream, resize it,
		# convert the frame to grayscale, and blur it
		frame = vs.read()
		#frame = imutils.resize(frame, width=400)		

		# grab the current timestamp and draw it on the frame
		timestamp = datetime.datetime.now()
				
		# detect motion in the image
		human = hd.detect(frame)

		# cehck to see if motion was found in the frame
		if human is not None:
			# unpack the tuple and draw the box surrounding the
			# "motion area" on the output frame
			human_number = human
			#cv2.rectangle(frame, (minX, minY), (maxX, maxY),(0, 0, 255), 2)		
							
		cv2.putText(frame, timestamp.strftime(
			"%A %d %B %Y %I:%M:%S%p"), (10, frame.shape[0] - 10),
			cv2.FONT_HERSHEY_SIMPLEX, 0.35, (0, 0, 255), 1)
		cv2.putText(frame, "[INFO] we found {} human".format(human_number), (50,50), cv2.FONT_HERSHEY_DUPLEX, 0.8,(0,0,255))
		#total += 1
		
		# send notification message if human is detected / only for the first time
		if human_number > 0 and send_message_flag == None:
			#sendText("ALERT", "[INFO] we found {:d} human".format(human_number))
			photo_name = 'E:/webXAMPP/robotic/media/human_detect_photo/human-detect-' + date + '.jpg'		
			cv2.imwrite(photo_name, frame)
			#sendFile(photo_name)
			send_message_flag = True
			
		# acquire the lock, set the output frame, and release the
		# lock
		with lock:
			outputFrame = frame.copy()
		if key == 27 or key == ord('q') or total == 100:
			#os.kill(os.getpid(), signal.SIGINT)
			#return jsonify({ "success": True, "message": "Server is shutting down..." })
			break
		
def generate():
	# grab global references to the output frame and lock variables
	global outputFrame, lock

	# loop over frames from the output stream
	while True:
		# wait until the lock is acquired
		with lock:
			# check if the output frame is available, otherwise skip
			# the iteration of the loop
			if outputFrame is None:
				continue

			# encode the frame in JPEG format
			(flag, encodedImage) = cv2.imencode(".jpg", outputFrame)

			# ensure the frame was successfully encoded
			if not flag:
				continue

		# yield the output frame in the byte format
		yield(b'--frame\r\n' b'Content-Type: image/jpeg\r\n\r\n' + 
			bytearray(encodedImage) + b'\r\n')

@app.route("/video_feed")
def video_feed():
	# return the response generated along with the specific media
	# type (mime type)
	#print("[INFO] call video feed...")
	return Response(generate(),
		mimetype = "multipart/x-mixed-replace; boundary=frame")
		#mimetype = "multipart/x-mixed-replace; boundary=--jpgboundary")
		
@app.route('/stop_detector')
def stop_detector():   
    print("[INFO] call stop detector...")
    os.kill(os.getpid(), signal.SIGINT)
    return jsonify({ "success": True, "message": "Server is shutting down..." })
        
    
		
		
# check to see if this is the main thread of execution
if __name__ == '__main__':
	# construct the argument parser and parse command line arguments
	ap = argparse.ArgumentParser()
	ap.add_argument("-i", "--ip", type=str, required=True,
		help="ip address of the device")
	ap.add_argument("-o", "--port", type=int, required=True,
		help="ephemeral port number of the server (1024 to 65535)")
	ap.add_argument("-f", "--frame-count", type=int, default=32,
		help="# of frames used to construct the background model")
	args = vars(ap.parse_args())

	# start a thread that will perform motion detection
	t = threading.Thread(target=detect_human, args=(
		args["frame_count"],))
	t.daemon = True
	t.start()

	# start the flask app
	app.run(host=args["ip"], port=args["port"], debug=True,
		threaded=True, use_reloader=False)

# release the video stream pointer
vs.stop()