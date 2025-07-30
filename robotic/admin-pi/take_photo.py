import cv2
import numpy as np
import datetime

date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")

# Create a VideoCapture object
vs = cv2.VideoCapture("http://192.168.1.111:8000/stream.mjpg")
#cap = cv2.VideoCapture(0)
cap = cv2.VideoCapture("http://192.168.1.111:8000/stream.mjpg")
cap.set(3, 1024)
cap.set(4, 768)
cap.set(6, cv2.VideoWriter.fourcc('M', 'J', 'P', 'G'))
# Check if camera opened successfully
if (cap.isOpened() == False): 
	print("Unable to read camera feed")


print("[INFO] Start camera record...")

ret, frame = cap.read()
#frame = cv2.flip(frame, -1)
if ret == True: 
	cv2.imwrite('E:/webXAMPP/robotic/media/images/photo_' + date + '.jpg',frame)
	# resize image
	resized = cv2.resize(frame, (300,200), interpolation = cv2.INTER_AREA)
	cv2.imwrite('E:/webXAMPP/robotic/media/images/thumbnail/photo_' + date + '.jpg',resized)
 
# When everything done, release the video capture and video write objects
cap.release()
 
# Closes all the frames
cv2.destroyAllWindows() 
print("[INFO] End camera record...")
