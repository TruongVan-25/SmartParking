#!/bin/bash

MYSQL_ROOT_PASSWORD=''  # Replace with your MySQL root password
DB_NAME='smart_parking'
IOT_USER='smartparking'
IOT_PASSWORD='cyber@2025'

# Create the database
mysql -u root -p$MYSQL_ROOT_PASSWORD -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"

# Create shared user
mysql -u root -p$MYSQL_ROOT_PASSWORD -e \
"CREATE USER IF NOT EXISTS '$IOT_USER'@'%' IDENTIFIED BY '$IOT_PASSWORD';
 GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$IOT_USER'@'%';
 FLUSH PRIVILEGES;"



echo "Setup completed: Database '$DB_NAME' with shared user '$IOT_USER' and student tables."
