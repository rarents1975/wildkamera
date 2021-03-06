# Wildkamera
Überwachungskamera auf Basis Raspberry Pi Zero. Die Kamera macht bei Bewegung alle 60 Sekunden ein Foto. Die Fotos werden auf der SD Karte des Raspberry Pi gespeichert und können über ein Web-Frontend z.B. über das Mobile Phone angeschaut u. auch wieder gelöscht werden. Dazu wird über einen Taster auf dem Raspberry Pi ein WLAN Hotspot erzeugt über den man sich z.B. mit dem Mobile Phone verbinden kann. 

Die Wildkamera bietet die folgende Funktionailität:   
* Bei Bewegung wird alle 60s ein Foto erstellt
* Bei kurzem Klick auf den Taster erstellt die Kamera einen WLAN Hotspot. Die LED leuchtet dann. Auf diesen kann man sich dann mit dem Handy verbinden. Bei Eingabe von 192.168.4.1 im Browser werden einem die Bilder angezeigt.
* Bilder können über das Browser-Frontend gelöscht werden  
* Bei langem Klick auf den Taster wird der Raspberry Pi ausgeschaltet. Die LED beginnt dann zu flackern.  

![alt text](https://github.com/rarents1975/wildkamera/blob/main/gehaeuse.jpg)

## Hardware
[Raspberry Pi Zero W](https://www.amazon.de/Jaimenalin-Raspberry-Zero-Board-Eingebautem-Gr%C3%BCn/dp/B08MF2NXNL/ref=sr_1_8?dchild=1&keywords=raspberry+pi+zero+w&qid=1620504314&sr=8-8)  
Micro SD Speicherkarte (z.B. 64GB)  
[AZDelivery Real Time Clock RTC DS3231 I2C](https://www.amazon.de/gp/product/B01M2B7HQB/ref=ppx_yo_dt_b_asin_title_o00_s00?ie=UTF8&psc=1)  
[Elektreeks PIR Sensor](https://www.amazon.de/gp/product/B079WCCND1/ref=ppx_yo_dt_b_asin_title_o06_s00?ie=UTF8&psc=1)  
[Electreeks Raspberry Pi Kamera Modul mit Automatik Infrarot-Sperrfilter](https://www.amazon.de/gp/product/B08C5GDG9Q/ref=ppx_yo_dt_b_asin_title_o09_s01?ie=UTF8&psc=1)  
Taster  
LED  

## Hardware & Verkabelung
Die Hardware wird wie auf dem Schaubild verkabelt. Darauf achten, daß die gpios aus der Abbildung verwendet werden, ansonsten müssen die [python Scripte](https://github.com/rarents1975/wildkamera/tree/main/rrapps) entsprechend angepasst werden.

![alt text](https://github.com/rarents1975/wildkamera/blob/main/verkabelung.jpg)

## Installation  

### Verzeichnis für Bilder und Scripte anlegen
Als user pi auf der Console anmelden und folgende Befehle ausführen:  
`cd`  
`mkdir /home/pi/rrapps`  
`sudo mkdir /video_recordings/dev`  
Jetzt im Verzeichnis für die Bilder dem user des Web Servers (www-data) entsprechende Lese- und Schreibberechtigungen vergeben:  
`sudo chwon -R www-data:www-data /video_recordings/dev` 

### Realtime Clock konfigurieren:  
Anleitung siehe auch unter: (https://delivery.shopifyapps.com/-/5ec669306e597ee9/97532a04d5640b8a)  


### WLAN Hotspot einrichten  
Anleitung siehe auch unter: (https://intux.de/2019/12/eigenes-wlan-netzwerk-mit-dem-raspberry-pi-realisieren/)

### Webserver installieren und konfigurieren (inkl. favicons)  
Anleitung siehe auch unter: (https://pimylifeup.com/raspberry-pi-lighttpd/)  

Den lighthttpd Webserver installieren:

`sudo apt-get update`  
`sudo apt-get upgrade`  
`sudo apt-get remove apache2`  
`sudo apt-get install lighttpd` 

Anschliessend Check über Browser ob der Webserver läuft:  
`hostname -I`  
Adresse im Browser eingeben. ie Startseite des lighthttpd sollte erscheinen  
Hinwei: Die Webseiten liegen im Verzeichnis /var/www/html auf dem Pi.  

Dann php installieren:  
`sudo apt-get install php7.3-fpm php7.3-mbstring php7.3-mysql php7.3-curl php7.3-gd php7.3-curl php7.3-zip php7.3-xml -y`

Lighthttpd konfigurieren:  
`sudo lighttpd-enable-mod fastcgi`  
`sudo lighttpd-enable-mod fastcgi-php`  

PHP Konfiguration:  
`sudo nano /etc/lighttpd/conf-available/15-fastcgi-php.conf` 

Inhalt muss folgendermassen aussehen:  
```
# -*- depends: fastcgi -*-
# /usr/share/doc/lighttpd/fastcgi.txt.gz
# http://redmine.lighttpd.net/projects/lighttpd/wiki/Docs:ConfigurationOptions#mod_fastcgi-fastcgi

## Start an FastCGI server for php (needs the php5-cgi package)
fastcgi.server += ( ".php" =>
        ((
                "socket" => "/var/run/php/php7.3-fpm.sock",
                "broken-scriptfilename" => "enable"
        ))
)
```  

Lighthttpd neu starten:  
sudo service lighttpd force-reload  

PHP testen indem eine Website erzeugt wird:  
`sudo nano /var/www/html/index.php`  
Folgendes einfügen:  
`<?php phpinfo() ?>`  

Anschliessend im Browser Ergebniss checken.  

### Web-Frontend installieren  

Die Inhalte des Verzeichnisses (https://github.com/rarents1975/wildkamera/tree/main/html) auf dem Raspi in das Verzeichnis /var/www/ kopieren.  

Dem Nutzer www-data Berechtigungen erteilen:  
`sudo chown -R www-data:www-data /var/www/html` 

config.txt anpassen:  
sudo nano /var/www/html/devadmin/config.txt  
Folgende Änderung vornehmen (je nach IP des Raspis im eigenerzeugten WLAN Netzwerk):  
`"server":"192.168.4.1",`  

### Eigenes WLAN Netzwerk für den Raspberry Pi einrichten:  
https://intux.de/2019/12/eigenes-wlan-netzwerk-mit-dem-raspberry-pi-realisieren/

### Stromverbrauch optimieren (bei Akkubetrieb)  
Anleitung siehe auch unter: (https://elektro.turanis.de/html/prj298/index.html)  
Folgende Verbraucher wie in der Anleitung beschrieben optimieren:  
* HDMI abschalten  
* Interne LEDs abschalten  
* Bluetooth abschalten  
* Ethernet abschalten  

### Konfiguration der rc.local mit den Scripten die beim Systemstart automatisch gestartet werden  
sudo nano /etc/rc.local  
in /etc/rc.local folgende Eintragung vornehhmen:  
```
# Print the IP address
_IP=$(hostname -I) || true
if [ "$_IP" ]; then
  printf "My IP address is %s\n" "$_IP"
fi

iptables-restore < /etc/iptables.ipv4.nat
/usr/bin/tvservice -o
sudo ifconfig wlan0 down
sudo /usr/bin/python /home/rarents/rrapps/camera3.py &
sudo /usr/bin/python3 /home/rarents/rrapps/checkwlan.py &
sudo /usr/bin/python3 /home/rarents/rrapps/reboot.py &
exit 0
```  




