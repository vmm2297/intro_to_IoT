#!/usr/bin/python
# Copyright (c) 2014 Adafruit Industries
# Author: Tony DiCola

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.
import sys
import time

import Adafruit_DHT
from SoundSensor import SoundSensor
from MCP3008ADC import MCP3008ADC
import RPi.GPIO as GPIO

import pymongo
from datetime import datetime
from pytz import timezone

### Variables ###
duration = 5
# DHT Pins
sensorDHT = 22
pinDHT = 26
# Sound Sensor Pins
pinGate = 21
pinEnvelope = 1
pinAudio = 0

GPIO.setmode(GPIO.BCM)
GPIO.setup(pinGate, GPIO.IN)

myMCP = MCP3008ADC()
mySoundSensor = SoundSensor(pinGate, pinEnvelope, pinAudio)

### Connect to MongoDB ###

connection = pymongo.MongoClient("mongodb://admin:admin@ds046867.mlab.com:46867/intro_to_iot")
db = connection.intro_to_iot
sounds = db.sound
humidities = db.hum
temperatures = db.temp

### DHT ###

while 1:
    dto = datetime.now(timezone('UTC'))
    dto_pacific = dto.astimezone(timezone('US/Pacific'))
    dts = datetime.strftime(dto_pacific,"%Y-%m-%d %H:%M:%S")
    humidity, temperature = Adafruit_DHT.read_retry(sensorDHT, pinDHT)
    temperature = temperature * 9/5.0 + 32
    gateVal = GPIO.input(mySoundSensor.get_gate())
    envelopeVal = myMCP.read(mySoundSensor.get_envelope())
    audioVal = myMCP.read(mySoundSensor.get_audio())
    if humidity is not None and temperature is not None:
        humidity_entry = {'time':dts, 'val':humidity}
        humidities.insert_one(humidity_entry)
        temperature_entry = {'time':dts, 'val':temperature}
        temperatures.insert_one(temperature_entry)
        sound_entry = {'time':dts, 'gate':gateVal, 'envelope':envelopeVal, 'audio':audioVal}
        sounds.insert_one(sound_entry)
        print('Temp={0:0.1f}* Humidity={1:0.1f}%'.format(temperature, humidity))
        time.sleep(duration)

### END DHT ###
