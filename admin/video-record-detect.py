import cv2
import numpy as np
import datetime
import argparse
import urllib.request

stream = urllib.request.urlopen('http://localhost:8000/video_feed?fps=5')
total_bytes = b''

date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")

#out = cv2.VideoWriter('E:/opencv-project/video_record/camera-' + date + '.mp4', 0x00000021, 20.0, (frame_width,frame_height))
out = cv2.VideoWriter('E:/webXAMPP/robotic/media/video/camera-' + date + '.mp4', 0x00000021, 20.0, (640,480))

print("[INFO] Start camera record...")
frame_number = 0
while(True):
	total_bytes += stream.read(1024)
	b = total_bytes.find(b'\xff\xd9') # JPEG end
	if not b == -1:
		a = total_bytes.find(b'\xff\xd8') # JPEG start
		jpg = total_bytes[a:b+2] # actual image
		total_bytes= total_bytes[b+2:] # other informations
        
        # decode to colored image ( another option is cv2.IMREAD_GRAYSCALE )
		frame = cv2.imdecode(np.frombuffer(jpg, dtype=np.uint8), cv2.IMREAD_COLOR)    
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

 
 
# Closes all the frames
cv2.destroyAllWindows() 
print("[INFO] End camera record...")
