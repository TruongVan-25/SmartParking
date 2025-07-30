'''
@Skyblue
check if no video stream process runs, start it. Otherwise, just quit to avoid duplicating process

'''
import psutil
import time
import os
import subprocess
 
def checkIfProcessRunning(processName):
    '''
    Check if there is any running process that contains the given name processName.
    '''
    #Iterate over the all the running process
    for proc in psutil.process_iter():
        try:
            # Check if process name contains the given name string.
            if processName.lower() in proc.name().lower():
                return True
        except (psutil.NoSuchProcess, psutil.AccessDenied, psutil.ZombieProcess):
            pass
    return False;
 
def findProcessIdByName(processName):
    '''
    Get a list of all the PIDs of a all the running process whose name contains
    the given string processName
    '''
 
    listOfProcessObjects = []
 
    #Iterate over the all the running process
    for proc in psutil.process_iter():
       try:
           pinfo = proc.as_dict(attrs=['pid', 'name', 'create_time'])
           # Check if process name contains the given name string.
           if processName.lower() in pinfo['name'].lower() :
               listOfProcessObjects.append(pinfo)
       except (psutil.NoSuchProcess, psutil.AccessDenied , psutil.ZombieProcess) :
           pass
 
    return listOfProcessObjects;
 
def main():
 
    
    print("*** Find PIDs of a running process by Name ***")
 
    # Find PIDs od all the running instances of process that contains 'chrome' in it's name
    listOfProcessIds = findProcessIdByName('python')
    print('Process Exists is {}'.format(len(listOfProcessIds)))
 
    if len(listOfProcessIds) == 1:
      # no video streaming since no python process (use python to stream video), start it!
       cmd = "python E:/webXAMPP/robotic/admin/stream-video-detection/webstreaming.py --ip 0.0.0.0 --port 8000"
       #os.system(cmd)
       subprocess.call(cmd, shell=True)
       #os.system("python check_process.py")
    else :
       print('Video streaming is already running...')
 
 
if __name__ == '__main__':
   main()