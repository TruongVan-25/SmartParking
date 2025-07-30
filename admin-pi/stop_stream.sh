# RasPi.vn 
#!/bin/bash
 
if pgrep python3  > /dev/null
then
sudo kill $(pgrep python3) > /dev/null 2>&1
echo "Stream video stops now..."
else
echo "Video stream does not run..."
fi
