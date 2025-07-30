#https://pypi.org/project/pushbullet.py/0.9.1/

from pushbullet import Pushbullet

print ("[INFO] PUSH msg start...")
pb = Pushbullet("o.KjnkK2lCCp01XL1P5w7MvUYK6UlQfi7P")
print(pb.devices)

def sendFile(file_name):
	with open(file_name, "rb") as pic:
	    file_data = pb.upload_file(pic, file_name)
	push = pb.push_file(**file_data)
	
def sendText(title, text_msg):
	dev = pb.get_device('Samsung SHV-E330L')
	push = dev.push_note(title, text_msg)

def sendSMS(sender_phone_number, text_msg):
	device = pb.get_device('Samsung SHV-E330L')
	sms = pb.push_sms(device, sender_phone_number, text_msg)
	
#sendText("INFO", "test msg 4")
#sendFile("test.jpg")
#sendSMS("+84985929351", "toi nay anh ko an com o nha nghe")

print ("[INFO] PUSH msg done...")
