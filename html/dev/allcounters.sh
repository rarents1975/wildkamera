#!/bin/bash

# Dieses Script ruft einmal in der Nacht aus der Crontab des Users www-data alle counter*.php Scripte auf, 
# und setzt damit die Werte in den counter*.txt Dateien um den Wert 1 hoch. Damit stimmt die Tagsanzeige in der 
# Statistikanzeige des Admin-Frontends

/usr/bin/php /usr/local/nginx/html/dev/zcounter.php
/usr/bin/php /usr/local/nginx/html/dev/zcounteraufz.php
/usr/bin/php /usr/local/nginx/html/dev/zcounterlive.php
/usr/bin/php /usr/local/nginx/html/dev/zcountermpeg.php
