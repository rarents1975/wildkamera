#!/bin/bash
# Erzeugt ein mp4 mit laufendem Text
#
#
# Parameter
ort=$(jq -r .ort ./config.txt)
pfad="/video_recordings/"$ort                 # Dieser Pfad muss editiert werden
#
#
texttxt="./scroller/text.txt"  # in dieser Datei steht der Text in einer Zeile
farbejpg="./scroller/gelb.jpg"   # vordefiniertes Bild mit der Hintergrundfarbe
ausgabe=$pfad"/scrolling.mp4"   # dort wird das Ergebnis hingeschrieben
hilfsdatei="./scroller/hilfsdatei.mp4"
#
#0.) Zeit für die Laenge des Videos aus der Textvorgabe berechnen (empirisch ermittelt)
text=$(head $texttxt)
#echo $text
anzahl=${#text}
echo Es wurden $anzahl Zeichen uebernommen. 
if [ $anzahl -eq 0 ]
then
   exit
fi
((zeit1=anzahl*100/65*20))
((zeit=zeit1/100))
((zeit=zeit+3))
//echo Die berechnete Videolaenge betraegt $zeit Sekunden.
sleep 5
#
#1.) Video aus jpg Hintergrund erzeugen
ffmpeg -y -loop 1 -i $farbejpg -c:v libx264 -t $zeit $ausgabe
#
#2.) Text einbrennen
ffmpeg -y -i $ausgabe -filter_complex \
"[0]split[txt][orig];[txt]drawtext=fontfile=tahoma.ttf:fontsize=70:fontcolor=black:x=200-100*t:y=90:\
textfile=$texttxt:bordercolor=black:line_spacing=20:borderw=3[txt];\
[orig]crop=iw:50:0:0[orig];[txt][orig]overlay" \
-c:v libx264 -y -preset ultrafast -t $zeit $hilfsdatei

#3.) Video beschneiden
ffmpeg -y -i $hilfsdatei -filter:v "crop=1280:100:0:65" $ausgabe

//echo Das Ergebnis steht auf $ausgabe
echo Das Lauftext-Video ist $zeit sec lang.
