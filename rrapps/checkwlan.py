# Diese Script erstellt bei Klick auf den Taster einen WLAN Hotspot. 
# Bei erneutem Klick wird der Hotspot wieder deaktiviert.

from gpiozero import Button
from gpiozero import LED
from time import sleep
import os
# meinbutton = Button(2)
meinbutton = Button(25)
meineled=LED(23)
ledstatus=0


while True:
    if ledstatus == 0:
      meinbutton.wait_for_press()
      meineled.on()
#      print("es wurde gedrueckt und der Prozessgestartet, LED ist ein")
      ledstatus=1
#      os.system("sudo systemctl start nginx")
      os.system("sudo ifconfig wlan0 up")
      os.system("sudo systemctl start hostapd")
      sleep(5)
    if ledstatus == 1:
      meinbutton.wait_for_press()
      ledstatus=0
      meineled.off()
#      print("es wurde gedrueckt und der Prozess gestoppt. LED ist aus")
      os.system("sudo systemctl stop hostapd")
      os.system("sudo ifconfig wlan0 down")
#      os.system("sudo systemctl stop nginx")
      sleep(5.0)
