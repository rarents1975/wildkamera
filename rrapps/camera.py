# Immer wenn der PIR Bewegung erfasst, wird ein Bild erzeugt: Vorschaubild u. Originalbild.

import RPi.GPIO as GPIO
import time, os, sys
import subprocess, datetime

SENSOR_PIN = 4

GPIO.setmode(GPIO.BCM)
GPIO.setup(SENSOR_PIN, GPIO.IN)

IMGDIR='/video_recordings/dev/'
PREVIMGDIR='/video_recordings/dev/images/'

while True:
    # PIR-Status lesen
    Bewegung = GPIO.input(SENSOR_PIN)
    if Bewegung == 1:
      timestamp = int(time.time()*1000.0)
      print "Bewegung"
      ts = time.time()
      rs = str(ts)
      x = rs.split(".")
      st = datetime.datetime.fromtimestamp(ts).strftime('-%A-%d-%m-%y-%H-%M')
      filename = IMGDIR + 'test-' + x[0] + str(st) + '.jpg'
      filenameSmall = PREVIMGDIR + 'test-' + x[0] + str(st) + '.jpg'
      cmd = 'raspistill -o ' + filename + ' -t 10 '
      pid = subprocess.call(cmd, shell=True)
      cmd2 = 'convert ' + filename + ' -resize 640 ' + filenameSmall 
      pid = subprocess.call(cmd2, shell=True)
      print filename
      time.sleep(60)
