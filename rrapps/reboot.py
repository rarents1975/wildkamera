# Bei 5s Klick auf den Taster, beginnt die LED zu flackern und der Raspberry Pi wird heruntergefahren.

from gpiozero import Button
from gpiozero import LED
from time import sleep
import os
#meinbutton = Button(2)
meinbutton = Button(25)

meineled=LED(23)


while True:
      meinbutton.wait_for_press()
#      print("es wurde gedrueckt")
      zeit=0
      while meinbutton.is_pressed:
         print(zeit)
         zeit=zeit+1
         sleep(1.0)
         if zeit > 5:
            i=0
            while i < 30:
              meineled.on()
              sleep(0.1)
              meineled.off()
              sleep(0.1)
              i=i+1
            os.system("sudo reboot now")
#            print("reboot")
