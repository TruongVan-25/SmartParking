import urllib
import urllib2

url = 'http://192.168.1.222/smart_home/includes/addface.php'
values = {'Name' : Name,'ID' : ID}


data = urllib.urlencode(values)    #encode the values from the dictionary.
req = urllib2.Request(url, data)    #combine the values and the url.
response = urllib2.urlopen(req)   #send the url open request and recieve the response.
