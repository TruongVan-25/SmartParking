#!/usr/bin/env python
# -*- coding: utf-8 -*-

"""
PyFingerprint
Copyright (C) 2015 Bastian Raschke <bastian.raschke@posteo.de>
All rights reserved.

"""

import hashlib
from pyfingerprint.pyfingerprint import PyFingerprint
import smbus
import time

# Define some device parameters
I2C_ADDR  = 0x3f # I2C device address
LCD_WIDTH = 16   # Maximum characters per line

# Define some device constants
LCD_CHR = 1 # Mode - Sending data
LCD_CMD = 0 # Mode - Sending command

LCD_LINE_1 = 0x80 # LCD RAM address for the 1st line
LCD_LINE_2 = 0xC0 # LCD RAM address for the 2nd line
LCD_LINE_3 = 0x94 # LCD RAM address for the 3rd line
LCD_LINE_4 = 0xD4 # LCD RAM address for the 4th line

LCD_BACKLIGHT  = 0x08  # On
#LCD_BACKLIGHT = 0x00  # Off

ENABLE = 0b00000100 # Enable bit

# Timing constants
E_PULSE = 0.0005
E_DELAY = 0.0005

#Open I2C interface
#bus = smbus.SMBus(0)  # Rev 1 Pi uses 0
bus = smbus.SMBus(1) # Rev 2 Pi uses 1

def lcd_init():
  # Initialise display
  lcd_byte(0x33,LCD_CMD) # 110011 Initialise
  lcd_byte(0x32,LCD_CMD) # 110010 Initialise
  lcd_byte(0x06,LCD_CMD) # 000110 Cursor move direction
  lcd_byte(0x0C,LCD_CMD) # 001100 Display On,Cursor Off, Blink Off 
  lcd_byte(0x28,LCD_CMD) # 101000 Data length, number of lines, font size
  lcd_byte(0x01,LCD_CMD) # 000001 Clear display
  time.sleep(E_DELAY)

def lcd_byte(bits, mode):
  # Send byte to data pins
  # bits = the data
  # mode = 1 for data
  # 0 for command

  bits_high = mode | (bits & 0xF0) | LCD_BACKLIGHT
  bits_low = mode | ((bits<<4) & 0xF0) | LCD_BACKLIGHT

  # High bits
  bus.write_byte(I2C_ADDR, bits_high)
  lcd_toggle_enable(bits_high)

  # Low bits
  bus.write_byte(I2C_ADDR, bits_low)
  lcd_toggle_enable(bits_low)

def lcd_toggle_enable(bits):
  # Toggle enable
  time.sleep(E_DELAY)
  bus.write_byte(I2C_ADDR, (bits | ENABLE))
  time.sleep(E_PULSE)
  bus.write_byte(I2C_ADDR,(bits & ~ENABLE))
  time.sleep(E_DELAY)

def lcd_string(message,line):
  # Send string to display

  message = message.ljust(LCD_WIDTH," ")

  lcd_byte(line, LCD_CMD)

  for i in range(LCD_WIDTH):
    lcd_byte(ord(message[i]),LCD_CHR)

def main():
  # Main program block

  # Initialise display
  
    lcd_init()

    # Send some test
    lcd_string("Hello",LCD_LINE_1)
    lcd_string("Welcome to our",LCD_LINE_2)
    lcd_string("Smart Home",LCD_LINE_3)

    time.sleep(3)
  
    # Send some more text
    #lcd_string("Hello<",LCD_LINE_1)
    #lcd_string("Welcome to our",LCD_LINE_2)
    #lcd_string("Smart Home",LCD_LINE_3)


    #time.sleep(3)

if __name__ == '__main__':

  try:
    main()
  except KeyboardInterrupt:
    pass
  finally:
    lcd_byte(0x01, LCD_CMD)

## Search for a finger
##

## Tries to initialize the sensor
while True:
    try:
        f = PyFingerprint('/dev/ttyUSB0', 115200, 0xFFFFFFFF, 0x00000000)

        if ( f.verifyPassword() == False ):
            raise ValueError('The given fingerprint sensor password is wrong!')

    except Exception as e:
        print('The fingerprint sensor could not be initialized!')
        print('Exception message: ' + str(e))
        exit(1)

    ## Gets some sensor information
    print('Currently used templates: ' + str(f.getTemplateCount()) +'/'+ str(f.getStorageCapacity()))

    ## Tries to search the finger and calculate hash

    try:
        lcd_init()
        print('Waiting for finger...')
        lcd_string("Waiting finger...",LCD_LINE_1)
        ## Wait that finger is read
        while ( f.readImage() == False ):
            pass
        ## Converts read image to characteristics and stores it in charbuffer 1
        f.convertImage(0x01)

        ## Searchs template
        result = f.searchTemplate()

        positionNumber = result[0]
        accuracyScore = result[1]

        if (positionNumber == -1):
            print('No match found!')
            lcd_string("no match found",LCD_LINE_2)
            time.sleep(1)
            exit(0)
        else:
            print('Found template at position #' + str(positionNumber))
            lcd_string("Found ID#" + str(positionNumber),LCD_LINE_1)
            time.sleep(3)
            print('The accuracy score is: ' + str(accuracyScore))
            
            if(positionNumber==1):
                lcd_string("Welcome Hang" ,LCD_LINE_1)
                time.sleep(3)
            if(positionNumber==2):
                lcd_string("Welcome Tien" ,LCD_LINE_1)
                time.sleep(3)
            if(positionNumber==3):
                lcd_string("Welcome Duc" ,LCD_LINE_1)
                time.sleep(3)
            if(positionNumber==0):
                lcd_string("Welcome Vinh" ,LCD_LINE_1)
                time.sleep(3)
            if(positionNumber==4):
                lcd_string("Welcome Vinh" ,LCD_LINE_1)
                time.sleep(3)
        ## OPTIONAL stuff
        ##

        ## Loads the found template to charbuffer 1
        f.loadTemplate(positionNumber, 0x01)

        ## Downloads the characteristics of template loaded in charbuffer 1
        characterics = str(f.downloadCharacteristics(0x01)).encode('utf-8')

        

    except Exception as e:
        print('Operation failed!')
        print('Exception message: ' + str(e))
        exit(1)
