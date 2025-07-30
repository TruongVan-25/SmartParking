import cv2
import numpy as np
import datetime

date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")

# Create a VideoCapture object
#cap = cv2.VideoCapture(0)
cap = cv2.VideoCapture("http://192.168.1.111:8000/stream.mjpg")
 
# Check if camera opened successfully
if (cap.isOpened() == False): 
  print("Unable to read camera feed")
 
# Default resolutions of the frame are obtained.The default resolutions are system dependent.
# We convert the resolutions from float to integer.
frame_width = int(cap.get(3))
frame_height = int(cap.get(4))
 
# Define the codec and create VideoWriter object.The output is stored in 'outpy.avi' file.
#out = cv2.VideoWriter('outpy.avi',cv2.VideoWriter_fourcc('M','J','P','G'), 10, (frame_width,frame_height))

# Define the codec and create VideoWriter object
#fourcc = cv2.VideoWriter_fourcc(*'XVID')
#out = cv2.VideoWriter('/home/deeplearning/vinh/video_record/camera_' + date + '.avi',fourcc, 20.0, (640,480))

fourcc = cv2.VideoWriter_fourcc(*'avc1')
#out = cv2.VideoWriter('E:/opencv-project/video_record/camera-' + date + '.mp4', 0x00000021, 20.0, (frame_width,frame_height))
out = cv2.VideoWriter('E:/webXAMPP/robotic/media/video/camera-' + date + '.mp4', 0x00000021, 20.0, (frame_width,frame_height))

print("[INFO] Start camera record...")
frame_number = 0
while(True):
	ret, frame = cap.read()
	#frame = cv2.flip(frame, -1)	
	if ret == True: 
		# Display the resulting frame    
		cv2.imshow('frame',frame)
		# Write the frame into the file 'output.avi'
		out.write(frame)
		# Press Q on keyboard to stop recording
		k = cv2.waitKey(1) & 0xFF
		frame_number += 1
		if k == 27 or k == ord('q') or frame_number == 200:
			break
		elif k == ord('s'): # wait for 's' key to save and exit
			cv2.imwrite('E:/webXAMPP/robotic/media/video/camera-' + date + '.jpg',frame)
	# Break the loop
	else:
		break 
 
# When everything done, release the video capture and video write objects
cap.release()
out.release()
 
# Closes all the frames
cv2.destroyAllWindows() 
print("[INFO] End camera record...")
