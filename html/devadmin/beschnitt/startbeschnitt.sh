#!/bin/bash
# Startet das Programm beschneiden.sh
#
xxx=$(pwd)   #liefert /usr/local/nginx/html/irgendingenadmin
moduladmin=${xxx##*/}  # liefert irgendingenadmin
modul=${moduladmin%%admin}  #liefert irgendingen
#echo modul=$modul
#echo moduladmin=$moduladmin
echo 1 > /usr/local/nginx/html/$moduladmin/beschnitt/statusbeschneiden.txt
bash /usr/local/nginx/html/$moduladmin/beschnitt/beschneiden.sh $1 $2 $3 $4 $5 $6 $7 $8 $9
echo 0 > /usr/local/nginx/html/$moduladmin/beschnitt/statusbeschneiden.txt

