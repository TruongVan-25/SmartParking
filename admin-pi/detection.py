# USAGE
# python detection.py --prototxt MobileNetSSD_deploy.prototxt --model MobileNetSSD_deploy.caffemodel --confidence 0.3
# Modify by @Skyblue

# import the necessary packages
from imutils.video import VideoStream
from imutils.video import FPS
import numpy as np
import argparse
import imutils
import time
import cv2
import datetime

date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")
# construct the argument parser and parse the arguments
ap = argparse.ArgumentParser()
ap.add_argument("-p", "--prototxt", required=True,
	help="path to Caffe 'deploy' prototxt file")
ap.add_argument("-m", "--model", required=True,
	help="path to Caffe pre-trained model")
ap.add_argument("-c", "--confidence", type=float, default=0.2,
	help="minimum probability to filter weak detections")
args = vars(ap.parse_args())

# initialize the list of class labels MobileNet SSD was trained to
# detect, then generate a set of bounding box colors for each class
CLASSES = ["background", "aeroplane", "bicycle", "bird", "boat",
	"bottle", "bus", "car", "cat", "chair", "cow", "diningtable",
	"dog", "horse", "motorbike", "person", "pottedplant", "sheep",
	"sofa", "train", "tvmonitor"]
COLORS = np.random.uniform(0, 255, size=(len(CLASSES), 3))

# load our serialized model from disk
print("[INFO] loading model...")
net = cv2.dnn.readNetFromCaffe(args["prototxt"], args["model"])

# initialize the video stream, allow the cammera sensor to warmup,
# and initialize the FPS counter
print("[INFO] starting video stream...")

#vs = VideoStream(src=0).start()
#vs = cv2.VideoCapture(0)
vs = cv2.VideoCapture("http://192.168.1.111:8000/stream.mjpg")
time.sleep(2.0)
fps = FPS().start()


# Define the codec and create VideoWriter object
#fourcc = cv2.VideoWriter_fourcc(*'MP4V')
fps_value = 5 # depend on your hardware
frame_width = int(vs.get(3))
frame_height = int(vs.get(4))
#video_out = cv2.VideoWriter('E:/opencv-project/video_record/object-detection_' + date + '.mp4',fourcc, fps_value, (frame_width,frame_height))
#video_out = cv2.VideoWriter('E:/opencv-project/video_record/object-detection-url-' + date + '.mp4', 0x00000021, fps_value, (frame_width,frame_height))
video_out = cv2.VideoWriter('E:/webXAMPP/robotic/media/video/object-detection-url-' + date + '.mp4', 0x00000021, fps_value, (frame_width,frame_height))

frame_number = 0
# start looping over all the frames
while True:
	
	# grab the frame from the threaded video stream and resize it
	# to have a maximum width of 400 pixels
	ret, frame = vs.read()
	if not ret:
		break
	frame_number += 1
	# Rotate the camera source 
	#frame = cv2.flip(frame, -1)
	#frame = imutils.resize(frame, width=400)
	(h, w) = frame.shape[:2]
	blob = cv2.dnn.blobFromImage(cv2.resize(frame, (300, 300)),
		0.007843, (300, 300), 127.5)

	# pass the blob through the network and obtain the detections and
	# predictions
	net.setInput(blob)
	detections = net.forward()
	human_number = 0

	# loop over the detections
	for i in np.arange(0, detections.shape[2]):
		# extract the confidence (i.e., probability) associated with
		# the prediction
		confidence = detections[0, 0, i, 2]

		# filter out weak detections by ensuring the confidence is
		# greater than the minimum confidence
		if confidence > args["confidence"]:
			# extract the index of the class label from the
			# detections
			idx = int(detections[0, 0, i, 1])
			box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
			(startX, startY, endX, endY) = box.astype("int")
 
			
			# count number of human dectected
			if CLASSES[idx] == 'person':
				human_number += 1
				# draw the prediction on the frame
				label = "{}: {:.2f}%".format(CLASSES[idx],
					confidence * 100)
				cv2.rectangle(frame, (startX, startY), (endX, endY),
					COLORS[idx], 2)
				y = startY - 15 if startY - 15 > 15 else startY + 15
				cv2.putText(frame, label, (startX, y),
					cv2.FONT_HERSHEY_SIMPLEX, 0.5, COLORS[idx], 2)

	
	cv2.putText(frame, "[INFO] we found {:d} human".format(human_number), (50,50), cv2.FONT_HERSHEY_DUPLEX, 0.8,(0,0,255))			
	# show the output frame
	cv2.imshow("Frame", frame)
	video_out.write(frame)
	print("[INFO] Total frame: {}".format(frame_number))
	key = cv2.waitKey(30) & 0xFF
 
	# if the `q` key was pressed, break from the loop
	# if key == ord("q"):
	if key == 27 or key == ord('q') or frame_number == 200:
		break
 
	# update the FPS counter
	fps.update()

# stop the timer and display FPS information
fps.stop()
print("[INFO] elapsed time: {:.2f}".format(fps.elapsed()))
print("[INFO] approx. FPS: {:.2f}".format(fps.fps()))

# do a bit of cleanup
cv2.destroyAllWindows()
vs.release()
video_out.release()
print("[INFO] Video record end!")
